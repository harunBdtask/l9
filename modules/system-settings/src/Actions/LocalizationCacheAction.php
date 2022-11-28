<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Actions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use SkylarkSoft\GoRMG\SystemSettings\Models\HeadingLocalization;

class LocalizationCacheAction
{
    public function execute()
    {
        Cache::forget('localizations');
        Cache::rememberForever('localizations', function () {
            return HeadingLocalization::query()->firstOr(function () {
                return (object)[
                    'localization' => []
                ];
            })->localization;
        });

        $this->generateLocalizationForVueJs();
    }

    public function generateLocalizationForVueJs()
    {
        $filePath = storage_path('lang/localization.json');

        if (!file_exists($filePath)) {
            return false;
        }

        File::put($filePath, json_encode(Cache::get('localizations',[]), JSON_PRETTY_PRINT));
    }
}
