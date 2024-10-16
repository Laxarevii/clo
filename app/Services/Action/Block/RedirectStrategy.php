<?php

namespace App\Services\Action\Block;

use App\Command\Resolve\DTO\BadResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class RedirectStrategy implements BlockActionStrategyInterface
{
    public function __construct(
        private string $url
    ) {
    }

    public function execute(BadResponse $response): RedirectResponse
    {
        return Redirect::to($this->url);
    }
}
