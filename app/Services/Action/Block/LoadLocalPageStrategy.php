<?php

namespace App\Services\Action\Block;

use App\Command\Resolve\DTO\BadResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class LoadLocalPageStrategy implements BlockActionStrategyInterface
{
    public function __construct(
        private string $localUrl,
    ) {
    }

    public function execute(BadResponse $response): RedirectResponse
    {
        return Redirect::to($this->localUrl);
    }
}
