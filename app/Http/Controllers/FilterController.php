<?php

namespace App\Http\Controllers;

use App\Command\Filter\Command;
use App\Command\Filter\CommandHandler;
use App\Entity\AcceptLanguage;
use App\Entity\Ip;
use App\Entity\Referer;
use App\Entity\UserAgent;
use App\Exceptions\NoAcceptLanguageException;
use App\Exceptions\NoUserAgentException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function __construct(private CommandHandler $handler)
    {
    }

    /**
     * @throws \App\Exceptions\NoAcceptLanguageException
     * @throws \App\Exceptions\NoUserAgentException
     */
    public function filter(Request $request)
    {
        return $this->handler->handle(new Command(
            $this->getAcceptLanguage($request),
            $this->getUserAgent($request),
            $this->getIp($request),
            $this->getReferer($request),
            $this->getUri($request),
        ));
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
