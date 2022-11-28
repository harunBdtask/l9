<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Actions\LocalizationCacheAction;
use SkylarkSoft\GoRMG\SystemSettings\Models\HeadingLocalization;

class HeadingLocalizationController extends Controller
{
    public function index()
    {
        $headLocalizations = HeadingLocalization::query()->first();
        return view('system-settings::localization.index', [
            'localizations' => $headLocalizations->localization ?? []
        ]);
    }

    public function store(Request $request, LocalizationCacheAction $action): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'main_label' => 'required',
            'localized' => 'required'
        ]);
        $localizationAttributes = [
            $request->input('main_label') => $request->input('localized')
        ];
        $headLocalization = HeadingLocalization::query()->firstOrNew(['id' => 1], [
            'localization' => []
        ]);

        $headLocalization->localization = array_merge($headLocalization->localization, $localizationAttributes);

        $headLocalization->save();
        $action->execute();

        Session::flash('alert-success', 'Localization Added Successfully');

        return back();
    }

    public function getLocalization($heading): string
    {
        return localizedFor($heading);
    }

    public function show()
    {
        return Cache::get('localizations');
    }
}
