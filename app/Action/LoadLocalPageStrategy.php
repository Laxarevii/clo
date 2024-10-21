<?php

namespace App\Action;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class LoadLocalPageStrategy implements ActionInterface
{
    public function __construct(
        private string $localUrl,
    ) {
    }

    public function execute(): RedirectResponse
    {
        return new RedirectResponse(URL::to($this->localUrl),200);
    }
}
