<?php

namespace App\Http\Controllers\API;

use App\Asset;
use App\Service\Converter;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Get(
 * path="/assets/convert/usd",
 * summary="Returns user's assets value in USD",
 * description="Returns user's assets value in USD",
 * operationId="convertAssets",
 * tags={"convertAssets"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="true"),
 *       @OA\Property(property="data", type="object"),
 *       @OA\Property(property="message", type="string", example="Conversion successful.")
 *    )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="User should be authorized to get conversion information",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthenticated"),
 *    )
 * )
 * ),
 * @OA\Get(
 * path="/assets/convert/{id}/usd",
 * summary="Returns user's asset value in USD",
 * description="Returns user's asset value in USD",
 * operationId="convertAsset",
 * tags={"convertAssets"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="true"),
 *       @OA\Property(property="data", type="object"),
 *       @OA\Property(property="message", type="string", example="Conversion successful.")
 *    )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="User should be authorized to get conversion information",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthenticated"),
 *    )
 * )
 * ),
 *

class ConvertController extends BaseController
{
    private Converter $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function convert(int $id = null): JsonResponse
    {
        try {
            $value = $this->converter->convert($id ? Asset::find($id) : Asset::all(), 'USD');
        } catch (\Exception $exception) {
            return $this->sendError('Conversion error', $exception->getMessage());
        }

        return $this->sendResponse([
            'usd_value' => number_format($value, 2),
            'asset_id' => $id ?? 'all user\'s assets'
        ], 'Conversion successful.');
    }
}
