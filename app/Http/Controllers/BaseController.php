<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * @OA\Info(
 *    title="ApplicationAPI",
 *    version="1.0.0",
 * )
 */

class BaseController extends Controller
{
    protected function sendResponse($result, string $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ], 200);
    }

    protected function sendError(string $error, $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
