<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Asset;
use \App\Http\Resources\Asset as AssetResource;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;

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
            'Product retrieved successfully.'
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
