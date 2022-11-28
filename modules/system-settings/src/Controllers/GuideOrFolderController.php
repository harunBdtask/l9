<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\GuideOrFolder;
use SkylarkSoft\GoRMG\SystemSettings\Requests\GuideOrFolderRequest;

class GuideOrFolderController extends Controller
{
    public function index()
    {
        $guide_or_folders = GuideOrFolder::paginate();

        return view('system-settings::iedroplets.guide_or_folders', ['guide_or_folders' => $guide_or_folders]);
    }

    public function create()
    {
        return view('system-settings::iedroplets.guide_or_folder', ['guide_or_folder' => null]);
    }

    public function store(GuideOrFolderRequest $request)
    {
        try {
            GuideOrFolder::create($request->all());
            Session::flash('success', S_SAVE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/guide-or-folders');
    }

    public function edit($id)
    {
        $guide_or_folder = GuideOrFolder::findOrFail($id);

        return view('system-settings::iedroplets.guide_or_folder', ['guide_or_folder' => $guide_or_folder]);
    }

    public function update($id, GuideOrFolderRequest $request)//
    {
        try {
            $guide_or_folder = GuideOrFolder::findOrFail($id);
            $guide_or_folder->update($request->all());

            Session::flash('success', S_UPDATE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/guide-or-folders');
    }

    public function destroy($id)
    {
        try {
            $guide_or_folder = GuideOrFolder::findOrFail($id);
            $guide_or_folder->delete();

            Session::flash('success', S_DELETE_MSG);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('guide-or-folders');
    }

    public function searchGuideOrFolder(Request $request)
    {
        $guide_or_folders = GuideOrFolder::where('name', 'like', '%' . $request->q . '%')
            ->paginate();

        return view('system-settings::iedroplets.guide_or_folders', ['guide_or_folders' => $guide_or_folders, 'q' => $request->q]);
    }
}
