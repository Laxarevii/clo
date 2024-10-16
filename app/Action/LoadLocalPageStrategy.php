<?php

namespace App\Action;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class LoadLocalPageStrategy implements ActionInterface
{
    public function __construct(
        private string $localUrl,
    ) {
    }

    public function execute(): RedirectResponse
    {
        return Redirect::to($this->localUrl);
    }
}
