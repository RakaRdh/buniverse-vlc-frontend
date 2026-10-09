<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class MediaController extends BaseController
{
    /**
     * Serve uploaded media files with local caching and reverse-proxy to CMS
     * Route: uploads/(:any)
     */
    public function serve(...$segments): ResponseInterface
    {
        $path = implode('/', $segments);

        // 1. Path Sanitization (Anti-Traversal & Null-byte security)
        $path = trim($path, '/\\');
        if (empty($path) || strpos($path, '..') !== false || strpos($path, "\0") !== false) {
            return $this->response->setStatusCode(400)->setBody('Invalid media path');
        }

        $cacheDir = WRITEPATH . 'cache/uploads/';
        $cacheFile = $cacheDir . $path;

        // 2. Check Local Disk Cache (Cache Hit)
        if (file_exists($cacheFile) && is_file($cacheFile)) {
            return $this->deliverFile($cacheFile);
        }

        // 3. Cache Miss: Fetch from internal CMS (port 8082)
        $apiConfig = config('Api');
        $cmsBaseUrl = preg_replace('#/api/?$#', '', $apiConfig->baseURL ?? 'http://localhost:8082');

        $candidateUrls = array_values(array_filter([
            getenv('CMS_INTERNAL_URL') ?: null,
            $cmsBaseUrl,
            'http://localhost:8082',
            'http://127.0.0.1:8082',
            'http://[::1]:8082',
        ]));

        $body = null;
        $client = \Config\Services::curlrequest([
            'timeout'     => 5.0,
            'http_errors' => false,
        ]);

        foreach ($candidateUrls as $base) {
            $cmsUrl = rtrim($base, '/') . '/uploads/' . $path;
            try {
                $res = $client->get($cmsUrl);
                if ($res->getStatusCode() === 200) {
                    $body = $res->getBody();
                    if (!empty($body)) {
                        break;
                    }
                }
            } catch (\Throwable $e) {
                // Try next candidate fallback
            }
        }

        if (empty($body)) {
            return $this->response->setStatusCode(404)->setBody('Media not found on CMS');
        }

        // 4. Save to local disk cache directory
        $targetSubdir = dirname($cacheFile);
        if (!is_dir($targetSubdir)) {
            mkdir($targetSubdir, 0755, true);
        }
        file_put_contents($cacheFile, $body);

        return $this->deliverFile($cacheFile);
    }

    /**
     * Stream binary media file with optimal HTTP cache headers
     */
    private function deliverFile(string $filePath): ResponseInterface
    {
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'webp' => 'image/webp',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'svg'  => 'image/svg+xml',
            'gif'  => 'image/gif',
            'ico'  => 'image/x-icon',
            'pdf'  => 'application/pdf',
            'mp4'  => 'video/mp4',
        ];

        $mimeType = $mimeTypes[$ext] ?? (function_exists('mime_content_type') ? @mime_content_type($filePath) : 'application/octet-stream') ?: 'application/octet-stream';
        $fileSize = filesize($filePath);
        $fileTime = filemtime($filePath);
        $etag = '"' . md5($fileTime . $fileSize) . '"';

        // Check 304 Not Modified
        $ifNoneMatch = $this->request->getServer('HTTP_IF_NONE_MATCH');
        if ($ifNoneMatch && trim($ifNoneMatch) === $etag) {
            return $this->response
                ->setStatusCode(304)
                ->setHeader('ETag', $etag)
                ->setHeader('Cache-Control', 'public, max-age=604800, stale-while-revalidate=86400');
        }

        return $this->response
            ->setStatusCode(200)
            ->setContentType($mimeType)
            ->setHeader('Content-Length', (string) $fileSize)
            ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', $fileTime) . ' GMT')
            ->setHeader('ETag', $etag)
            ->setHeader('Cache-Control', 'public, max-age=604800, stale-while-revalidate=86400')
            ->setBody(file_get_contents($filePath));
    }
}
