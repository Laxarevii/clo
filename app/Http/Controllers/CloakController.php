<?php

namespace App\Http\Controllers;

use App\Command\Resolve\Command;
use App\Command\Resolve\Interface\CommandHandlerInterface;
use App\Command\Resolve\CommandHandler;
use App\Common\DTO\AcceptLanguage;
use App\Common\DTO\Ip;
use App\Common\DTO\Referer;
use App\Common\DTO\UserAgent;
use App\Exceptions\NoAcceptLanguageException;
use App\Exceptions\NoUserAgentException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CloakController extends Controller
{
    /**
     * @param CommandHandler $commandHandler
     */
    public function __construct(protected CommandHandlerInterface $commandHandler)
    {
    }

    public function resolve(Request $request): JsonResponse
    {
        $result = $this->commandHandler->handle(
            new Command(
                $this->getAcceptLanguage($request),
                $this->getUserAgent($request),
                $this->getIp($request),
                $this->getReferer($request),
                $this->getUri($request),
            )
        );

        /** @var \Illuminate\Routing\ResponseFactory $response */
        $response = response();

        return $response->json(['status' => $result->getStatus(), 'message' => $result->getMessage()]);
    }

    private function getAcceptLanguage(Request $request): AcceptLanguage
    {
        $acceptLanguage = $request->header('Accept-Language');

        if (!is_string($acceptLanguage)) {
            throw new NoAcceptLanguageException();
        }

        return new AcceptLanguage($acceptLanguage);
    }


    private function getUserAgent(Request $request): UserAgent
    {
        $userAgent = $request->header('User-Agent');

        if (!is_string($userAgent)) {
            throw new NoUserAgentException();
        }

        return new UserAgent($userAgent);
    }


    /** @psalm-suppress UnusedParam */
    private function getIp(Request $request): Ip
    {
        //TODO refactor
        return new Ip('89.209.161.249');
//         return new Ip($request->ip());
    }

    private function getReferer(Request $request): Referer
    {
        return new Referer($request->headers->get('referer'));
    }

    private function getUri(Request $request): Uri
    {
        return new Uri($request->getRequestUri());
    }
}
