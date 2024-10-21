<?php

namespace Tests\Unit\Command\Resolve;

use App\Action\ActionInterface;
use App\Command\Resolve\Interface\CheckHandlerInterface;
use App\Command\Common\DTO\Response;
use App\Command\Resolve\Command;
use App\Command\Resolve\CommandHandler;
use App\Command\Resolve\Wrapper\HandlerWrapChainInterface;
use App\Entity\AcceptLanguage;
use App\Entity\Ip;
use App\Entity\Referer;
use App\Entity\UserAgent;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class CommandHandlerTest extends TestCase
{
    private CommandHandler $commandHandler;
    private $handlerChainMock;

    protected function setUp(): void
    {
        $this->handlerChainMock = $this->createMock(HandlerWrapChainInterface::class);

        $this->commandHandler = new CommandHandler($this->handlerChainMock);
    }

    public function testHandleCallsHandlerChainWithCommand()
    {
        $acceptLanguage = new AcceptLanguage('en-US,en;q=0.9,ru;q=0.8');
        $userAgent = new UserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36');
        $ip = new Ip('89.209.161.249');
        $referer = new Referer(null);
        $uri = new Uri('/api/resolve');

        $command = new Command($acceptLanguage, $userAgent, $ip, $referer, $uri);

        $actionMock = $this->createMock(ActionInterface::class);

        $this->handlerChainMock
            ->method('handle')
            ->with($command)
            ->willReturn($actionMock);

        $result = $this->commandHandler->handle($command);

        $this->assertSame($actionMock, $result);
    }
}
