<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\Merchandising\Filters\Filter;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\ArchiveFile;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ArchiveFileRequest;
use Str;
use Symfony\Component\HttpFoundation\Response;

class ArchiveFileController extends Controller
{
    public function index(Request $request)
    {
        $archiveFiles = $this->getArchiveList($request);
        $buyers = Buyer::query()->select(['id', 'name'])->get();

        return view("system-settings::archive_files.index", compact('archiveFiles', 'buyers'));
    }

    public function create()
    {
        $buyers = Buyer::query()->select(['id', 'name'])->get();
        return view("system-settings::archive_files.archive-create", compact('buyers'));
    }

    /**
     * @param ArchiveFileRequest $request
     * @return JsonResponse
     */
    public function store(ArchiveFileRequest $request): JsonResponse
    {
        try {
            $formData = [];
            $fileNames = $request['file_names'];
            foreach ($request->file('files') as $key => $file) {
                $extension = $file->getClientOriginalExtension();
                $fileUploadName = Str::snake($fileNames[$key]) . "_" . time() . "." . $extension;
                $fileUploadPath = "archived";
                Storage::disk('uploaded_file')->putFileAs($fileUploadPath, $file, $fileUploadName);
                $formData[] = [
                    'buyer_id' => $request->get('buyer_id', null),
                    'style_id' => $request->get('style_id', null),
                    'style' => $request->get('style', null),
                    'archive_type' => $request->get('archive_type', null),
                    'file' => $fileUploadPath . "/" . $fileUploadName,
                    'file_name' => $fileNames[$key],
                    'remarks' => $request['remarks'][$key],
                    'factory_id' => factoryId(),
                    'created_at' => date('Y-m-d-H:i:s'),
                    'updated_at' => date('Y-m-d-H:i:s'),
                ];
            }

            ArchiveFile::query()->insert($formData);
            $message = "Successfully Uploaded Files";
            session()->flash('success', $message);
            return response()->json(["message" => $message], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit($id)
    {
        try {
            $view['archiveFile']=ArchiveFile::with(['factory','buyer','order'])->findOrFail($id);
            $view['buyers'] = Buyer::query()->select(['id', 'name'])->get();
            return view("system-settings::archive_files.archive-edit", $view);
        }catch (\Exception $exception){
            return redirect()->back();
        }

    }
    public function update(Request $request){
        try {
           $archiveFile=ArchiveFile::query()->findOrFail($request->get('id'));
           $archiveFile->fill($request->all());
           $archiveFile->save();
           return redirect('archive-file')->with('success','Update Succesfully');
        }catch (\Exception $exception){
            return back()->with('error','Update Faild!');
        }
    }
    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function getArchiveList($request): LengthAwarePaginator
    {
        $search = $request->get('search');
        $style = $request->get('style');
        $styleId = $request->get('style_id');
        $buyerId = $request->get('buyer_id');

        return ArchiveFile::query()
            ->when($search, function (Builder $query) use ($search) {
                return $query->where('file_name', 'LIKE', "%$search%")
                    ->orWhere('remarks', 'LIKE', "%$search%")
                    ->orWhere('style', 'LIKE', "%$search%")
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        return $query->where('name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('order', function ($query) use ($search) {
                        return $query->where('style_name', 'LIKE', "%$search%");
                    });
            })
            ->when($buyerId, Filter::applyFilter('buyer_id', $buyerId))
            ->when($styleId, Filter::applyFilter('style_id', $styleId))
            ->when($style, Filter::applyFilter('style', $style))
            ->paginate();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function deleteArchiveFile($id): RedirectResponse
    {
        ArchiveFile::query()->findOrFail($id)->delete();
        session()->flash('success', 'Deleted Archive Files');
        return redirect()->back();
    }

    public function buyerWiseStyle($id): JsonResponse
    {
        $styleName = ArchiveFile::query()->whereNotNull('style')
            ->where('buyer_id', $id)
            ->select('style', 'archive_type')
            ->get()->map(function ($style) {
                return [
                    'style' => $style->style,
                    'archive_type' => 'previous',
                    'id' => 0
                ];
            })->toArray();

        $styleId = ArchiveFile::query()
            ->where('buyer_id', $id)
            ->whereNotNull('style_id')
            ->pluck('style_id')
            ->unique()->values()
            ->toArray();

        $orderStyle = Order::query()->whereIn('id', $styleId)
            ->get()->map(function ($style) {
                return [
                    'style' => $style->style_name,
                    'archive_type' => 'current',
                    'id' => $style->id
                ];
            })->toArray();

        $style = array_merge($styleName, $orderStyle);
        return response()->json($style, Response::HTTP_OK);
    }
}
