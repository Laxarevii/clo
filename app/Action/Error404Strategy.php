<?php

namespace App\Action;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Error404Strategy implements ActionInterface
{
    public function execute(): JsonResponse
    {
        return new JsonResponse(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
    }
}
