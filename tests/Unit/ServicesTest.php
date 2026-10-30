<?php

namespace WenexPlatform\PhpSdk\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use WenexPlatform\PhpSdk\Platform;

/**
 * Exercises the service methods added/changed for parity with the Node SDK
 * against a Guzzle mock handler (no network).
 */
final class ServicesTest extends TestCase
{
    /** @var \ArrayObject<int, array{request: RequestInterface, options: array<string, mixed>}> */
    private \ArrayObject $history;

    /**
     * @param list<Response> $responses
     */
    private function platform(array $responses): Platform
    {
        /** @var \ArrayObject<int, array{request: RequestInterface, options: array<string, mixed>}> $history */
        $history = new \ArrayObject([]);
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create(
            function (RequestInterface $request, array $options) use ($mock, $history) {
                /** @var array{request: RequestInterface, options: array<string, mixed>} $entry */
                $entry = ['request' => $request, 'options' => $options];
                $history[] = $entry;

                return $mock($request, $options);
            },
        );
        $this->history = $history;

        $client = new Client([
            'handler' => $stack,
            'base_uri' => 'https://api.example.com/',
        ]);

        return Platform::build($client);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function json(array $payload): Response
    {
        return new Response(200, ['Content-Type' => 'application/json'], (string) json_encode($payload));
    }

    private function request(int $index = 0): RequestInterface
    {
        $entry = $this->history[$index] ?? null;

        self::assertIsArray($entry);
        self::assertArrayHasKey('request', $entry);
        self::assertInstanceOf(RequestInterface::class, $entry['request']);

        return $entry['request'];
    }

    /**
     * @return array<int|string, mixed>
     */
    private function query(int $index = 0): array
    {
        $query = [];
        parse_str($this->request($index)->getUri()->getQuery(), $query);

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function body(int $index = 0): array
    {
        $decoded = json_decode((string) $this->request($index)->getBody(), true);

        return is_array($decoded) ? $decoded : [];
    }

    public function testProductsSearchPostsBodyAndFilter(): void
    {
        $platform = $this->platform([$this->json(['data' => ['items' => [['id' => 'p1']]]])]);

        $result = $platform->getCareer()->getProducts()->search(
            ['query' => ['match_all' => (object) []]],
            ['query' => ['status' => 'published']],
            ['headers' => ['X-Test' => 'yes']],
        );

        self::assertSame([['id' => 'p1']], $result['items']);
        self::assertSame('/career/products/search', $this->request()->getUri()->getPath());
        self::assertSame('match_all', array_key_first($this->body()['query']));
        self::assertSame(['status' => 'published'], $this->query()['query']);
        self::assertSame('yes', $this->request()->getHeaderLine('X-Test'));
    }

    public function testCountHonoursFullResponseAndDoesNotLeakConfig(): void
    {
        $platform = $this->platform([
            $this->json(['total' => 7]),
            $this->json(['total' => 7]),
        ]);

        $users = $platform->getIdentity()->getUsers();

        self::assertSame(['total' => 7], $users->count([], ['fullResponse' => true]));
        self::assertSame(7, $users->count(['status' => 'active'], ['params' => ['zone' => 'own']]));

        self::assertArrayNotHasKey('fullResponse', $this->query(0));
        self::assertSame('active', $this->query(1)['status']);
        self::assertSame('own', $this->query(1)['zone']);
        self::assertArrayNotHasKey('params', $this->query(1));
    }

    public function testFindForwardsHeadersAndUnwrapsItems(): void
    {
        $platform = $this->platform([$this->json(['items' => [['id' => 'u1']]])]);

        $items = $platform->getIdentity()->getUsers()->find(
            ['query' => []],
            ['headers' => ['X-Request-Id' => '42']],
        );

        self::assertSame([['id' => 'u1']], $items);
        self::assertSame('42', $this->request()->getHeaderLine('X-Request-Id'));
    }

    public function testFilesUploadSendsMultipartAndUnwrapsItems(): void
    {
        $platform = $this->platform([$this->json(['items' => [['id' => 'f1']]])]);

        $items = $platform->getSpecial()->getFiles()->upload(
            [['value' => 'file-bytes', 'filename' => 'note.txt']],
            'public',
        );

        self::assertSame([['id' => 'f1']], $items);

        $request = $this->request();
        $body = (string) $request->getBody();

        self::assertSame('POST', $request->getMethod());
        self::assertSame('/special/files/upload/public', $request->getUri()->getPath());
        self::assertStringContainsString('multipart/form-data', $request->getHeaderLine('Content-Type'));
        self::assertStringContainsString('name="file"', $body);
        self::assertStringContainsString('filename="note.txt"', $body);
        self::assertStringContainsString('file-bytes', $body);
    }

    public function testFilesUploadRejectsUnknownScope(): void
    {
        $platform = $this->platform([]);

        /** @var string $scope */
        $scope = 'world';

        $this->expectException(\InvalidArgumentException::class);

        $platform->getSpecial()->getFiles()->upload([['value' => 'x']], $scope);
    }

    public function testFilesDownloadReturnsRawBody(): void
    {
        $platform = $this->platform([
            new Response(200, ['Content-Type' => 'application/octet-stream'], "\x00binary\xff"),
        ]);

        $contents = $platform->getSpecial()->getFiles()->download('file-1');

        self::assertSame("\x00binary\xff", $contents);
        self::assertSame('/special/files/download/file-1', $this->request()->getUri()->getPath());
    }

    public function testFilesDownloadCanReturnAStream(): void
    {
        $platform = $this->platform([
            new Response(200, ['Content-Type' => 'application/octet-stream'], 'streamed'),
        ]);

        $stream = $platform->getSpecial()->getFiles()->download('file-1', [], 'stream');

        self::assertInstanceOf(StreamInterface::class, $stream);
        self::assertSame('streamed', (string) $stream);
    }
}
