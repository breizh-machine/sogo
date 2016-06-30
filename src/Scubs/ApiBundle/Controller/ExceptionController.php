<?php

namespace Scubs\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionController extends Controller
{
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null, $_format = 'html')
    {
        $jsonData = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ];
        return new JsonResponse($jsonData);
    }
}