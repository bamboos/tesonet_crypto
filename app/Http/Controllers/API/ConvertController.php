<?php

namespace App\Http\Controllers\API;

use App\Asset;
use App\Service\Converter;
use Illuminate\Http\JsonResponse;

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
