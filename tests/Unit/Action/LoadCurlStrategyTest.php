<?php
namespace Tests\Unit\Action;

use App\Action\LoadCurlStrategy;
use App\Services\Curl\CurlServiceInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

class LoadCurlStrategyTest extends TestCase
{
    private LoadCurlStrategy $loadCurlStrategy;
    private CurlServiceInterface $curlServiceMock;

    protected function setUp(): void
    {
        $this->curlServiceMock = Mockery::mock(CurlServiceInterface::class);
        $this->loadCurlStrategy = new LoadCurlStrategy('http://example.com', $this->curlServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testExecuteReturnsHtmlResponse(): void
    {
        $htmlContent = '<html><head><link rel="stylesheet" href="/styles.css"></head><body></body></html>';
        $this->curlServiceMock->shouldReceive('execute')
            ->with('http://example.com')
            ->andReturn($htmlContent);

        $response = $this->loadCurlStrategy->execute();

        $this->assertEquals(200, $response->status());
        $this->assertStringContainsString('<html>', $response->getContent());
    }

    public function testExecuteHandlesCurlException(): void
    {
        $this->curlServiceMock->shouldReceive('execute')
            ->andThrow(new \Exception('Curl error'));

        $response = $this->loadCurlStrategy->execute();

        $this->assertEquals(500, $response->status());
        $this->assertStringContainsString('Curl error', $response->getContent());
    }

    public function testConvertRelativeUrlsToAbsolute(): void
    {
        $html = '<html><head><link rel="stylesheet" href="/styles.css"><script src="script.js"></script></head><body></body></html>';
        $expectedHtml = '<html><head><link rel="stylesheet" href="http://example.com/styles.css"><script src="http://example.com/script.js"></script></head><body></body></html>';

        $reflection = new \ReflectionClass($this->loadCurlStrategy);
        $method = $reflection->getMethod('convertRelativeUrlsToAbsolute');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->loadCurlStrategy, [$html]);

        $this->assertEquals($expectedHtml, $result);
    }
}
