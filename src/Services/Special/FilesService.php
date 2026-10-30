<?php

namespace WenexPlatform\PhpSdk\Services\Special;

use GuzzleHttp\Client as HttpClient;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use WenexPlatform\PhpSdkCommon\Core\Classes\RestfulService;
use WenexPlatform\PhpSdkCommon\Core\Enums\Collection;
use WenexPlatform\PhpSdkCommon\Core\Utils\Http;

class FilesService extends RestfulService
{
    /** @var string[] */
    private const SCOPES = ['private', 'public'];

    public function __construct(HttpClient $httpClient, string $pathPrefix = '/')
    {
        parent::__construct(Collection::SPECIAL_FILES, $httpClient, $pathPrefix);
    }

    /**
     * Download a file by ID.
     *
     * The body is returned verbatim, matching the SDK's default `blob`
     * response type. Pass `responseType: 'stream'` (or `stream => true` in the
     * config) to receive the PSR-7 stream, or Guzzle's `sink` option to write
     * it to a file.
     *
     * @param array<string, mixed> $config
     * @param 'blob'|'arraybuffer'|'string'|'stream' $responseType
     */
    public function download(string $id, array $config = [], string $responseType = 'blob'): string|StreamInterface
    {
        $wantsStream = (bool) ($config['stream'] ?? ($responseType === 'stream'));

        $options = Http::getOptions($config);
        unset($options['stream']);

        if ($wantsStream) {
            $options['stream'] = true;
        }

        $response = $this->httpClient->get($this->url('download/' . $id), $options);
        $body = $response->getBody();

        return $wantsStream ? $body : (string) $body;
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
        $response = $this->post($this->url($id . '/share'), $data, Http::getOptions($config));

        return ($config['fullResponse'] ?? false) ? $response : $response['data'];
    }

    /**
     * Upload files via multipart/form-data.
     *
     * Each item is appended under the `file` field unless it carries its own
     * `name`, mirroring the Node SDK's `FormData` behaviour.
     *
     * @param array<int, array{value: mixed, filename?: string, name?: string}> $items
     * @param string $scope "private" or "public"
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public function upload(array $items, string $scope, array $config = []): array
    {
        if (!in_array($scope, self::SCOPES, true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid file scope `%s`; expected one of: %s.',
                $scope,
                implode(', ', self::SCOPES),
            ));
        }

        $multipart = [];

        foreach ($items as $item) {
            $part = [
                'name' => $item['name'] ?? 'file',
                'contents' => $item['value'],
            ];

            if (isset($item['filename'])) {
                $part['filename'] = $item['filename'];
            }

            $multipart[] = $part;
        }

        $options = Http::getOptions($config);
        $options['multipart'] = $multipart;

        $response = $this->post($this->url('upload/' . $scope), null, $options);

        return ($config['fullResponse'] ?? false) ? $response : $response['items'];
    }
}
