<?php

namespace App\Action;

interface ActionInterface
{
    public const CURL = 'curl';
    public const REDIRECT = 'redirect';

    public function execute();
}
