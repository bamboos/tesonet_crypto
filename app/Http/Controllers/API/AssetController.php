<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Asset;
use \App\Http\Resources\Asset as AssetResource;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;

/**
 * @OA\Get(
 * path="/assets",
 * summary="Retrieve all user's assets",
 * description="Retrieve all user's assets",
 * operationId="assetsList",
 * tags={"assets"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="true"),
 *       @OA\Property(property="data", type="array",
 *          @OA\Items(
 *              type="object"
 *          )
 *       ),
 *       @OA\Property(property="message", type="string", example="Assets retrieved successfully.")
 *    )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="User should be authorized to get assets information",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthenticated"),
 *    )
 * )
 * ),
 * @OA\Get(
 * path="/assets/{id}",
 * summary="Retrieve user's asset",
 * description="Retrieve user's asset",
 * operationId="assetsGetOne",
 * tags={"assets"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="true"),
 *       @OA\Property(property="data", type="object"),
 *       @OA\Property(property="message", type="string", example="Asset retrieved successfully.")
 *    )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="User should be authorized to get assets information",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthenticated"),
 *    )
 * )
 * ),
 * @OA\Post(
 * path="/assets",
 * summary="Store asset",
 * description="Store asset",
 * operationId="assetsStore",
 * tags={"assets"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass new asset information",
 *    @OA\JsonContent(
 *       required={"label","currency","value"},
 *       @OA\Property(property="label", type="string", example="some item"),
 *       @OA\Property(property="currency", type="string", example="BTC|ETH|IOTA"),
 *       @OA\Property(property="value", type="string", example="0.89"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Asset created successfully",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="true"),
 *       @OA\Property(property="message", type="string", example="Asset created successfully"),
 *       @OA\Property(property="data", type="object", example="Asset object"),
 *     )
 * ),
* @OA\Response(
 *    response=404,
 *    description="Validation error",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="false"),
 *       @OA\Property(property="message", type="string", example="Validation error"),
 *       @OA\Property(property="data", type="object", example="Errors object"),
 *     )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorised",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthorised")
 *    )
 * )
 * ),
@OA\Put(
 * path="/assets/{id}",
 * summary="Update asset",
 * description="Update asset",
 * operationId="assetsUpdate",
 * tags={"assets"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass updated asset information",
 *    @OA\JsonContent(
 *       required={"label","currency","value"},
 *       @OA\Property(property="label", type="string", example="some item"),
 *       @OA\Property(property="currency", type="string", example="BTC|ETH|IOTA"),
 *       @OA\Property(property="value", type="string", example="0.89"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Asset updated successfully",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="true"),
 *       @OA\Property(property="message", type="string", example="Asset created successfully"),
 *       @OA\Property(property="data", type="object", example="Asset object"),
 *     )
 * ),
 * @OA\Response(
 *    response=404,
 *    description="Validation error",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="false"),
 *       @OA\Property(property="message", type="string", example="Validation error"),
 *       @OA\Property(property="data", type="object", example="Errors object"),
 *     )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorised",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthorised")
 *    )
 * )
 * ),
 * @OA\Delete(
 * path="/assets/{id}",
 * summary="Delete user's asset",
 * description="Delete user's asset",
 * operationId="assetsDelete",
 * tags={"assets"},
 * security={ {"bearer": {} }},
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="success", type="bool", example="true"),
 *       @OA\Property(property="message", type="string", example="Asset deleted successfully.")
 *    )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="User should be authorized to get assets information",
 *    @OA\JsonContent(
 *       @OA\Property(property="message", type="string", example="Unauthenticated"),
 *    )
 * )
 * ),
 */

class AssetController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->sendResponse(
            AssetResource::collection(Asset::all()),
            'Assets retrieved successfully.'
        );
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'label' => ['required', 'unique:assets'],
            'currency' => [
                'required',
                Rule::in(['BTC', 'ETH', 'IOTA'])
            ],
            'value' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input['user_id'] = Auth::user()->getAuthIdentifier();

        return $this->sendResponse(
            new AssetResource(Asset::create($input)),
            'Asset created successfully.'
        );
    }

    public function show($id): JsonResponse
    {
        $asset = Asset::find($id);

        if (is_null($asset)) {
            return $this->sendError('Asset not found.');
        }

        return $this->sendResponse(
            new AssetResource($asset),
            'Asset retrieved successfully.'
        );
    }

    public function update(Request $request, Asset $asset): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'label' => ['required', 'unique:assets'],
            'currency' => [
                'required',
                Rule::in(['BTC', 'ETH', 'IOTA'])
            ],
            'value' => ['required', 'numeric']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $asset->label = $input['label'];
        $asset->currency = $input['currency'];
        $asset->value = $input['value'];
        $asset->save();

        return $this->sendResponse(
            new AssetResource($asset),
            'Asset updated successfully.'
        );
    }

    public function destroy(Asset $asset): JsonResponse
    {
        $asset->delete();

        return $this->sendResponse([], 'Asset deleted successfully.');
    }
}
