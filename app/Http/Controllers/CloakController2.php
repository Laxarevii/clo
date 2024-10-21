<?php

namespace App\Http\Controllers;

use App\Command\Resolve\Block\Command;
use App\Command\Resolve\Block\Interface\CommandHandlerInterface;
use App\Entity\Ip;
use Illuminate\Http\Request;

class CloakController2 extends Controller
{
    public function __construct(
        private CommandHandlerInterface $commandHandler,
    ) {
    }

    public function resolve(Request $request)
    {
        $command = new Command(
            $this->getIp($request),
        );
        $result = $this->commandHandler->handle($command);
        return $this->resolver->resolve($result);
    }

    /** @psalm-suppress UnusedParam */
    private function getIp(Request $request): Ip
    {
        //TODO refactor
        return new Ip('89.209.161.249');
//         return new Ip($request->ip());
    }
}
