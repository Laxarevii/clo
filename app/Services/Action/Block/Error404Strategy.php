<?php

namespace App\Services\Action\Block;

use App\Command\Resolve\DTO\BadResponse;
use Symfony\Component\HttpFoundation\Response;

class Error404Strategy implements BlockActionStrategyInterface
{
    public function execute(BadResponse $response)
    {
        return response()->json(['error' => 'Not Found'], Response::HTTP_NOT_FOUND);
    }
}
