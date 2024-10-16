<?php

namespace App\Action;

use Symfony\Component\HttpFoundation\Response;

class Error404Strategy implements ActionInterface
{
    public function execute(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
    }
}
