<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SkylarkSoft\GoRMG\SystemSettings\Models\MerchandisingVariableSettings;

class MerchandisingVariableSettingsController
{
    public function index()
    {
        return view("system-settings::merchandising_variable_settings.merchandising_variable_settings");
    }

    public function store(Request $request)
    {
        try {
            $prev_settings = MerchandisingVariableSettings::query()
                ->where("factory_id", $request->factory_id)
                ->where("buyer_id", $request->buyer_id)
                ->first();
            $is_update = "Saved";
            if (! $prev_settings) {
                $settings = new MerchandisingVariableSettings();
                $settings->fill($request->all())->save();
            } else {
                $is_update = "Updated";
                $prev_settings->fill($request->all())->save();
            }

            return response()->json([
                "message" => "Merchandising Variable Settings {$is_update} Successfully",
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $response = MerchandisingVariableSettings::where("factory_id", $id)->first();

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function loadPreviousData($factoryId, $buyerId): \Illuminate\Http\JsonResponse
    {
        try {
            $response = MerchandisingVariableSettings::where("factory_id", $factoryId)->where('buyer_id', $buyerId)->first();

            return response()->json($response, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function multipleBuyerWiseSave(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
        ]);

        try {
            foreach ($request->get('buyer_ids') as $buyerId) {
                $prev_settings = MerchandisingVariableSettings::query()
                    ->where("factory_id", $request->factory_id)
                    ->where("buyer_id", $buyerId)
                    ->first();

                $newData = [
                    'factory_id' => $request->get('factory_id'),
                    'buyer_id' => $buyerId,
                    'variables_name' => $request->get('variables_name'),
                    'variables_details' => $request->get('variables_details'),
                ];

                if (! $prev_settings) {
                    MerchandisingVariableSettings::query()->create($newData);
                } else {
                    $prev_settings->update($newData);
                }
            }

            return response()->json([
                "message" => "Merchandising Variable Settings Created Successfully",
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return \response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
