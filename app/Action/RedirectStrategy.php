<?php

namespace App\Action;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class RedirectStrategy implements ActionInterface
{
    public function __construct(
        private string $url,
        private int $status = 303
    ) {
    }

    public function execute(): RedirectResponse
    {
        return Redirect::to($this->url, $this->status);
    }
}
