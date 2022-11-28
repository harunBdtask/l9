<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Exception\TeamNameNotWellFormedException;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\POFileModel;
use SkylarkSoft\GoRMG\Merchandising\Models\TechPackFile;
use SkylarkSoft\GoRMG\Merchandising\Requests\TechPackFilesFormRequest;
use SkylarkSoft\GoRMG\Merchandising\Services\TechPackFile\TechPackExcelConverter;
use SkylarkSoft\GoRMG\PoPDF\TechPackConverter;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use Throwable;

class TechPackFilesController extends Controller
{
    const STORAGE_TECH_PACK_FILES = "/storage/tech_pack_files/";
    const TECH_PACK_FILES = "/tech_pack_files/";

    public function index(Request $request)
    {
        $styles = Order::all(['style_name']);
        $tech_pack_files = TechPackFile::query()
            ->when($request->query('search'), function ($query) use ($request) {
                $query->where("style", "LIKE", "%{$request->query('search')}%");
            })->orderBy("id", "DESC")->paginate();
            $totalFiles = TechPackFile::all()->count();
           
            $dashboardOverview = [
                "Total File" => $totalFiles
            ];

        return view("merchandising::tech-pack-files.files", [
            'styles' => $styles,
            'tech_pack_files' => $tech_pack_files,
            'dashboardOverview'=>$dashboardOverview,
        ]);
    }

    /**
     * @param TechPackFilesFormRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(TechPackFilesFormRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        $tech_pack_file = new TechPackFile();
        $requested_data = $request->all();
        if ($request->hasFile('file')) {
            $file = $request->file;
            $path = $request->get("style") . "." . $file->extension();
            $original_path = $request->get("style") . "_original" . "." . $file->extension();
            $file->storeAs('tech_pack_files', $path);
            $requested_data['file'] = $path;
            $requested_data['processed'] = 1;
            $conversion_file_path = public_path(self::STORAGE_TECH_PACK_FILES . $path);
            if ($file->extension() === "pdf") {
                $text_file_path = self::TECH_PACK_FILES . str_replace(".pdf", ".txt", $path);
                $original_file_path = self::TECH_PACK_FILES . str_replace(".pdf", ".txt", $original_path);
                $converter = new TechPackConverter($conversion_file_path);
                $content = $converter->getContent();
            } else {
                $text_file_path = self::TECH_PACK_FILES . str_replace(".xlsx", ".txt", $path);
                $original_file_path = self::TECH_PACK_FILES . str_replace(".xlsx", ".txt", $original_path);
                try {
                    $converter = new TechPackExcelConverter($conversion_file_path);
                    $content = $converter->convert($request->get('style'), $request->get('creeper_count'), $request->get('body_part_count'));
                } catch (TeamNameNotWellFormedException $exception) {
                    Session::flash('alert-danger', $exception->getMessage());
                    return back();
                }
            }
            Storage::put($text_file_path, $content);
            Storage::put($original_file_path, $content);
            $tech_pack_file->fill($requested_data)->save();
        }
        Session::flash('alert-success', 'Data stored successfully!');
        DB::commit();

        return redirect()->route('tech-pack-content', ['id' => $tech_pack_file]);
    }

    public function editContent($id)
    {
        $techPack = TechPackFile::query()->findOrFail($id);
        $extension = explode(".", $techPack->file);
        $searchExtension = $extension[1] == 'pdf' ? '.pdf' : '.xlsx';
        $text_file_path = self::STORAGE_TECH_PACK_FILES . str_replace($searchExtension, ".txt", $techPack->file);
        $content = File::get(public_path($text_file_path));
        $collection = collect(json_decode($content, true))->groupBy(function ($item, $key) {
            return substr($item['creeper'], 0, 9);
        });

        return view("merchandising::tech-pack-files.content-editor", [
            'tech_pack' => $techPack,
            'content' => $content,
            'collection' => $collection
        ]);
    }

    public function updateContent($id, Request $request)
    {
        try {
            $content = $request->get('content');
            $techPack = TechPackFile::query()->findOrFail($id);
            $extension = explode(".", $techPack->file);
            $searchExtension = $extension[1] == 'pdf' ? '.pdf' : '.xlsx';
            $text_file_path = self::TECH_PACK_FILES . str_replace($searchExtension, ".txt", $techPack->file);

            if (is_array($request->get('creeper'))) {
                $data = array();
                foreach ($request->get('creeper') as $key => $value) {
                    $data[$key]['color'] = $request->get('color')[$key];
                    $data[$key]['style'] = $request->get('style')[$key];
                    $data[$key]['creeper'] = $request->get('creeper')[$key];
                    $data[$key]['contrast_color'] = $request->get('contrast_color')[$key];
                }
                $content = json_encode($data);
            }
            Storage::put($text_file_path, $content);
            $techPack->update([
                'edit_status' => 1,
            ]);
            Session::flash('alert-success', 'File content updated successfully!');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong");
        } finally {
            return redirect('/tech-pack-files');
        }
    }

    public function download($id)
    {
        $tech_pack_file = TechPackFile::query()->where("id", $id)->first();
        if (isset($tech_pack_file->file) && Storage::disk('public')->exists(self::TECH_PACK_FILES . $tech_pack_file->file)) {
            $file_name = Storage::disk('public')->path(self::TECH_PACK_FILES . $tech_pack_file->file);
            return response()->download($file_name);
        } else {
            Session::flash('alert-danger', "File not found");
        }

        return back();
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $tech_pack_file = TechPackFile::query()->where("id", $id)->first();
            if (isset($tech_pack_file->file) && Storage::disk('public')->exists(self::TECH_PACK_FILES . $tech_pack_file->file)) {
                Storage::delete('tech_pack_files/' . $tech_pack_file->file);
            }
            $tech_pack_file->delete();
            Session::flash('alert-success', 'Data Deleted successfully!');

            return redirect()->back();
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!{$exception->getMessage()}");

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $techPackFile = TechPackFile::query()->findOrFail($id);
        $prevColor = Color::all();

        return view('merchandising::tech-pack-files.edit', compact('techPackFile', 'prevColor'));
    }

    public function viewContent($id)
    {
        $techPackFile = TechPackFile::query()->findOrFail($id);
        $extension = explode(".", $techPackFile->file);
        $conversion_file_path = public_path(self::STORAGE_TECH_PACK_FILES . $techPackFile->file);
        if ($extension[1] == 'pdf') {
            $converter = new TechPackConverter($conversion_file_path);
        } else {
            $converter = new TechPackExcelConverter($conversion_file_path);
        }
        return $converter->convert($techPackFile->style, $techPackFile->creeper_count, $techPackFile->body_part_count);
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws Throwable
     */
    public function processContent($id): RedirectResponse
    {

        DB::beginTransaction();
        $techPackFile = TechPackFile::query()->findOrFail($id);
        $conversion_file_path = public_path(self::STORAGE_TECH_PACK_FILES . $techPackFile->file);
        $extension = explode(".", $techPackFile->file);
        if ($extension[1] == 'pdf') {
            $converter = new TechPackConverter($conversion_file_path);
            $convert_response = $converter->convert($techPackFile->style, $techPackFile->creeper_count, $techPackFile->body_part_count);
            $response = json_decode($convert_response);
        } elseif ($extension[1] == 'xlsx') {
            try {
                $text_file_path = self::TECH_PACK_FILES . str_replace("xlsx", "txt", $techPackFile->file);
                if (Storage::exists($text_file_path)) {
                    $response = Storage::get($text_file_path);
                    $response = json_decode($response);
                } else {
                    $converter = new TechPackExcelConverter($conversion_file_path);
                    $response = json_decode($converter->convert($techPackFile->style,
                        $techPackFile->creeper_count,
                        $techPackFile->body_part_count));
                }
            } catch (TeamNameNotWellFormedException $exception) {
                Session::flash('alert-danger', "{$exception->getMessage()}");
                return redirect()->back();
            }
        }
        if (!isset($response)) {
            Session::flash('alert-danger', 'File Not Found');
            return back();
        }
        $getStyle = collect($response)->pluck('style')->first();
        Color::query()->where('style', $getStyle)->delete();
        $contrast_color = collect($response)->map(function ($value) {
            $color = Color::query()->firstOrCreate([
                'name' => $value->color,
            ], [
                'factory_id' => factoryId(),
                'status' => Color::COLOR_TYPE['team'],
            ]);

            return [
                'name' => $value->contrast_color,
                'parent_id' => $color->id,
                'tag' => $value->creeper,
                'style' => $value->style,
                'status' => Color::COLOR_TYPE['fabric_color'],
                'factory_id' => factoryId(),
                'created_by' => auth()->user()->id,
            ];
        })->where('name', '!=', null)->toArray();
        Color::query()->insert($contrast_color);
        $techPackFile->update([
            'contents' => $response,
        ]);
        DB::commit();
        $count_empty_contrast = collect($response)->where('contrast_color', null)
            ->count();
        if ($count_empty_contrast > 0) {
            Session::flash('alert-danger', "Some color couldn't read from pdf , please edit them manually.");
        }

        return redirect()->back();
    }

    public function update($id, Request $request)
    {
        try {
            foreach ($request->get('colors') as $key => $editableColor) {
                if ($request['contrast_colors'][$key] !== null) {
                    $color = Color::query()->firstOrCreate([
                        'name' => $editableColor,
                    ], [
                        'factory_id' => factoryId(),
                        'status' => Color::COLOR_TYPE['team'],
                    ]);

                    $data = [
                        'parent_id' => $color->id,
                        'tag' => $request->get('creepers')[$key],
                        'style' => $request->get('styles')[$key],
                        'status' => Color::COLOR_TYPE['fabric_color'],
                    ];
                    $targetColor = Color::where($data)->first();
                    $data = collect($data)->merge([
                        'name' => $request['contrast_colors'][$key] ?? '',
                        'factory_id' => factoryId(),
                        'created_by' => Auth::id(),
                    ])->toArray();
                    if (!$targetColor) {
                        $newColor = new Color();
                        $newColor->fill($data)->save();
                    } else {
                        $targetColor->update($data);
                    }
                }
            }

            Session::flash('alert-success', "Successfully Edited!");

            return redirect('/tech-pack-files');
        } catch (Exception $exception) {
            Session::flash('alert-danger', "Something Went Wrong {$exception->getMessage()}");

            return redirect()->back();
        }
    }

}
