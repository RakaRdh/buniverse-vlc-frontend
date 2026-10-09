<?php

namespace App\Services;

use Config\Services;
use Config\Api as ApiConfig;

class ApiService
{
    protected string $baseURL;
    protected int $timeout;
    protected $client;

    public function __construct()
    {
        $config = config('Api');
        $this->baseURL = rtrim($config->baseURL ?? 'http://localhost:8082/api/', '/') . '/';
        $this->timeout = $config->timeout ?? 10;
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
        } catch (\Exception $e) {
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
     * GET /api/programs
     */
    public function getPrograms(): array
    {
        $res = $this->request('GET', 'programs');
        if (!empty($res['success']) && is_array($res['data'])) {
            return $res['data'];
        }
        return [];
    }

    /**
     * GET /api/programs/(:any)
     */
    public function getProgramDetail($slugOrId): ?array
    {
        if (empty($slugOrId)) {
            return null;
        }

        $res = $this->request('GET', 'programs/' . urlencode((string)$slugOrId));
        if (!empty($res['success']) && !empty($res['data'])) {
            return $res['data'];
        }
        return null;
    }

    /**
     * GET /api/galleries
     */
    public function getGalleries(): array
    {
        $res = $this->request('GET', 'galleries');
        if (!empty($res['success']) && is_array($res['data'])) {
            return $res['data'];
        }
        return [];
    }

    /**
     * GET /api/faqs
     */
    public function getFaqs(): array
    {
        $res = $this->request('GET', 'faqs');
        if (!empty($res['success']) && is_array($res['data'])) {
            return $res['data'];
        }
        return [];
    }

    /**
     * POST /api/auth/login
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
     * POST /api/auth/register
     */
    public function register(array $data): array
    {
        return $this->request('POST', 'auth/register', [
            'form_params' => $data,
        ]);
    }

    /**
     * GET /api/profile/(:num)
     */
    public function getProfile($memberId): array
    {
        return $this->request('GET', 'profile/' . (int)$memberId);
    }

    /**
     * POST /api/profile/(:num)
     */
    public function updateProfile($memberId, array $data): array
    {
        return $this->request('POST', 'profile/' . (int)$memberId, [
            'form_params' => $data,
        ]);
    }

    /**
     * POST /api/enrollments
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
     * GET /api/enrollments/member/(:num)
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
