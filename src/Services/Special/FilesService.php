<?php

namespace WenexPlatform\PhpSdk\Services\Special;

use GuzzleHttp\Client as HttpClient;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class FilesService extends RestfulService
{
    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::SPECIAL_FILES, $httpClient, $pathPrefix);
    }

    /**
     * Download a file's raw contents by ID.
     *
     * Returns the response body as a string (the body is returned verbatim when the
     * server responds with a non-JSON content type). For very large files, prefer
     * configuring Guzzle stream options directly on the shared client.
     *
     * @param array<string, mixed> $config
     * @return string|array<string, mixed>
     */
    public function download(string $id, array $config = []): string|array
    {
        return $this->get($this->url('download/' . $id), ['query' => Http::get_params($config)]);
    }

    /**
     * Create a share link for a file.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function share(string $id, array $data, array $config = []): array
    {
        $response = $this->post($this->url($id . '/share'), $data, ['query' => Http::get_params($config)]);

        return $config['fullResponse'] ?? false ? $response : $response['data'];
    }

    /**
     * Upload files via multipart/form-data.
     *
     * @param array<int, array{value: mixed, filename?: string}> $items
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function upload(array $items, string $scope, array $config = []): array
    {
        throw new \BadMethodCallException(
            'files.upload() multipart upload is not yet implemented; see README (deferred feature).'
        );
    }
}
