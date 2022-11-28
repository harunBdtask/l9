<?php

namespace SkylarkSoft\GoRMG\Merchandising\Actions;

use SkylarkSoft\GoRMG\Merchandising\Models\FeatureVersion;

class FeatureVersionAction
{
    public function handle($featureName, $featureId)
    {
        $featureVersion = FeatureVersion::query()->firstOrNew([
            'feature_id' => $featureId,
            'feature_name' => $featureName
        ]);
        $featureVersion->save();
        $featureVersion->increment('version');

    }

    public function attach($featureName, $featureId, $association, $associateId)
    {
        $associateVersion = FeatureVersion::query()->where([
                'feature_name' => $association,
                'feature_id' => $associateId
            ])->first()->version ?? 1;

        $featureVersion = FeatureVersion::query()->updateOrCreate([
            'feature_id' => $featureId,
            'feature_name' => $featureName,
        ], ['version' => $associateVersion]);
        $featureVersion->save();

    }

    public function detach($featureName, $featureId)
    {
        FeatureVersion::query()->where([
            'feature_name' => $featureName,
            'feature_id' => $featureId
        ])->delete();
    }
}
