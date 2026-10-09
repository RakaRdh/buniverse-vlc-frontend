<?php

namespace App\Services;

use Config\Services;
use Config\Api as ApiConfig;

class ApiService
{
    protected string $baseURL;
    protected int $timeout;
    protected $client;
    protected int $cacheTtl;
    protected int $backupCacheTtl;

    public function __construct()
    {
        $config = config('Api');
        $this->baseURL = rtrim($config->baseURL ?? 'http://localhost:8082/api/', '/') . '/';
        $this->timeout = $config->timeout ?? 10;
        
        $isDev = (defined('ENVIRONMENT') && \ENVIRONMENT === 'development') || (getenv('CI_ENVIRONMENT') === 'development');
        $this->cacheTtl = $isDev ? 60 : 3600; // 1 min di dev, 1 jam di prod (di-purge instan via webhook CMS)
        $this->backupCacheTtl = 86400 * 30;   // 30 hari sebagai persistent backup cache

        $this->client = Services::curlrequest([
            'base_uri'    => $this->baseURL,
            'timeout'     => $this->timeout,
            'http_errors' => false,
            'headers'     => [
                'Accept'       => 'application/json',
                'User-Agent'   => 'VLC-Frontend-Client/1.0',
            ],
        ]);
    }

    /**
     * Send HTTP request to CMS REST API
     */
    protected function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->client->request($method, ltrim($endpoint, '/'), $options);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            return [
                'status'  => $statusCode,
                'success' => $statusCode >= 200 && $statusCode < 300,
                'message' => 'Invalid JSON response from API server',
                'data'    => null,
                'raw'     => $body,
            ];
        } catch (\Throwable $e) {
            log_message('error', 'ApiService Error [' . $endpoint . ']: ' . $e->getMessage());
            return [
                'status'  => 500,
                'success' => false,
                'message' => 'Gagal terhubung ke API backend: ' . $e->getMessage(),
                'data'    => null,
            ];
        }
    }

    /**
     * Get unified active bundle (programs, galleries, faqs)
     * Cached under single key: vlc_fe_active
     * Fallback to single backup: vlc_fe_active_backup
     */
    public function getActiveBundle(): array
    {
        $cacheKey = 'vlc_fe_active';
        $backupKey = 'vlc_fe_active_backup';

        $cached = cache($cacheKey);
        if ($cached !== null && is_array($cached) && !empty($cached)) {
            return $cached;
        }

        $res = $this->request('GET', 'active');
        if (!empty($res['success']) && is_array($res['data'])) {
            cache()->save($cacheKey, $res['data'], $this->cacheTtl);
            cache()->save($backupKey, $res['data'], $this->backupCacheTtl);
            return $res['data'];
        }

        // Failover: API CMS down/timeout -> fallback ke single persistent backup cache
        $backup = cache($backupKey);
        if ($backup !== null && is_array($backup)) {
            log_message('warning', 'Using fallback vlc_fe_active_backup');
            return $backup;
        }

        return [
            'programs'  => [],
            'galleries' => [],
            'faqs'      => [],
        ];
    }

    /**
     * Clear all live data cache in Frontend (Triggered by CMS webhook)
     * CATATAN: vlc_fe_active_backup TIDAK dihapus agar tetap menjadi jaring pengaman.
     */
    public function clearDataCache(): void
    {
        try {
            cache()->delete('vlc_fe_active');
            cache()->delete('vlc_fe_programs_active');
            cache()->delete('vlc_fe_galleries_active');
            cache()->delete('vlc_fe_faqs_active');
        } catch (\Throwable $e) {
            log_message('error', 'Failed clearing Frontend live data cache: ' . $e->getMessage());
        }
    }

    /**
     * GET Programs (Reads from unified vlc_fe_active bundle)
     */
    public function getPrograms(): array
    {
        $bundle = $this->getActiveBundle();
        return $bundle['programs'] ?? [];
    }

    /**
     * GET Program Detail (Checks vlc_fe_active first, falls back to endpoint/backup)
     */
    public function getProgramDetail($slugOrId): ?array
    {
        if (empty($slugOrId)) {
            return null;
        }

        // 1. Check in unified active bundle for instant memory lookup
        $bundle = $this->getActiveBundle();
        if (!empty($bundle['programs'])) {
            foreach ($bundle['programs'] as $p) {
                if ((string)$p['id'] === (string)$slugOrId || (!empty($p['slug']) && $p['slug'] === $slugOrId)) {
                    if (!empty($p['modules'])) {
                        return $p;
                    }
                }
            }
        }

        // 2. Fetch specific detail from API
        $res = $this->request('GET', 'programs/' . urlencode((string)$slugOrId));
        if (!empty($res['success']) && !empty($res['data'])) {
            return $res['data'];
        }

        // 3. Fallback: check inside vlc_fe_active_backup
        $backup = cache('vlc_fe_active_backup');
        if (!empty($backup['programs'])) {
            foreach ($backup['programs'] as $p) {
                if ((string)$p['id'] === (string)$slugOrId || (!empty($p['slug']) && $p['slug'] === $slugOrId)) {
                    return $p;
                }
            }
        }

        return null;
    }

    /**
     * GET Galleries (Reads from unified vlc_fe_active bundle)
     */
    public function getGalleries(): array
    {
        $bundle = $this->getActiveBundle();
        return $bundle['galleries'] ?? [];
    }

    /**
     * GET FAQs (Reads from unified vlc_fe_active bundle)
     */
    public function getFaqs(): array
    {
        $bundle = $this->getActiveBundle();
        return $bundle['faqs'] ?? [];
    }

    /**
     * POST /api/auth/login (Never cached - real-time transactional)
     */
    public function login(string $email, string $password): array
    {
        return $this->request('POST', 'auth/login', [
            'form_params' => [
                'email'    => $email,
                'password' => $password,
            ],
        ]);
    }

    /**
     * POST /api/auth/register (Never cached - real-time transactional)
     */
    public function register(array $data): array
    {
        return $this->request('POST', 'auth/register', [
            'form_params' => $data,
        ]);
    }

    /**
     * GET /api/profile/(:num) (Never cached - real-time member account state)
     */
    public function getProfile($memberId): array
    {
        return $this->request('GET', 'profile/' . (int)$memberId);
    }

    /**
     * POST /api/profile/(:num) (Never cached - updates member biodata)
     */
    public function updateProfile($memberId, array $data): array
    {
        return $this->request('POST', 'profile/' . (int)$memberId, [
            'form_params' => $data,
        ]);
    }

    /**
     * POST /api/enrollments (Never cached - transactional enrollment submit)
     */
    public function enroll($memberId, $programId, ?string $phone = null, ?string $notes = null): array
    {
        return $this->request('POST', 'enrollments', [
            'form_params' => [
                'member_id'  => $memberId,
                'program_id' => $programId,
                'phone'      => $phone,
                'notes'      => $notes,
            ],
        ]);
    }

    /**
     * GET /api/enrollments/member/(:num) (Never cached - real-time status)
     */
    public function getMemberEnrollments($memberId): array
    {
        $res = $this->request('GET', 'enrollments/member/' . (int)$memberId);
        if (!empty($res['success']) && is_array($res['data'])) {
            return $res['data'];
        }
        return [];
    }
}
