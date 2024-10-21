<?php

namespace Tests\Unit\Command\Resolve\Handler;

use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Common\DTO\BadResponse;
use App\Command\Common\DTO\SuccessResponse;
use App\Command\Resolve\Command;
use App\Command\Resolve\Handler\LanguageCheckHandler;
use App\Entity\AcceptLanguage;
use App\Entity\Language;
use App\Services\Detector\LanguageDetector\LanguageDetectorInterface;
use PHPUnit\Framework\TestCase;

class LanguageCheckHandlerTest extends TestCase
{
    private $languageDetectorMock;
    private $nextHandlerMock;
    private $commandMock;
    private $languageMock;
    private $acceptLanguageMock; // Добавляем мок для AcceptLanguage
    private array $allowedLanguages = ['en', 'fr'];

    protected function setUp(): void
    {
        $this->languageDetectorMock = $this->createMock(LanguageDetectorInterface::class);
        $this->nextHandlerMock = $this->createMock(CheckHandlerInterface::class);
        $this->commandMock = $this->createMock(Command::class);
        $this->languageMock = $this->createMock(Language::class);
        $this->acceptLanguageMock = $this->createMock(AcceptLanguage::class);

        $this->commandMock->method('getAcceptLanguage')->willReturn($this->acceptLanguageMock);
    }

    public function testHandleReturnsBadResponseForBlockedLanguage(): void
    {
        $this->languageMock->method('getValue')->willReturn('ru');
        $this->languageDetectorMock->method('detect')->with($this->acceptLanguageMock)->willReturn($this->languageMock);

        $handler = new LanguageCheckHandler($this->allowedLanguages, $this->languageDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(BadResponse::class, $response);
        $this->assertEquals('Blocked language', $response->getMessage());
    }

    public function testHandleDelegatesToNextHandlerForAllowedLanguage(): void
    {
        $this->languageMock->method('getValue')->willReturn('en');
        $this->languageDetectorMock->method('detect')->with($this->acceptLanguageMock)->willReturn($this->languageMock);
        $this->nextHandlerMock->method('handle')->with($this->commandMock)->willReturn(new SuccessResponse());

        $handler = new LanguageCheckHandler($this->allowedLanguages, $this->languageDetectorMock);
        $handler->setNext($this->nextHandlerMock);

        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }

    public function testHandleReturnsSuccessResponseIfNoNextHandler(): void
    {
        $this->languageMock->method('getValue')->willReturn('fr');
        $this->languageDetectorMock->method('detect')->with($this->acceptLanguageMock)->willReturn($this->languageMock);

        $handler = new LanguageCheckHandler($this->allowedLanguages, $this->languageDetectorMock);
        $response = $handler->handle($this->commandMock);

        $this->assertInstanceOf(SuccessResponse::class, $response);
    }
}
