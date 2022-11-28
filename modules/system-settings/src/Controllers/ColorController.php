<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ColorRequest;
use SkylarkSoft\GoRMG\Merchandising\Models\PoColorSizeBreakdown;

class ColorController extends Controller
{
    public function index(Request $request)
    {
            $view['search'] = $request->get('search');
            // $view['colors'] = Color::withTrashed()->with('factory')
            $view['colors'] = Color::with('factory')
                ->where('name', 'like', '%' . $view['search'] . '%')
                ->orWhere('status', 'like', '%' . $view['search'] . '%')
                ->orderBy('id', 'DESC')
                ->paginate(20);
        return view('system-settings::pages.colors', $view);
    }

    public function show($name): array
    {
        $colors = Color::query()->where('name', $name)->get();
        return ['name' => $colors->first()['name'], 'status' => $colors->pluck('status')];
    }

    public function store(ColorRequest $request)
    {
        $colors = [];
        if (count($request->get('status')) !== 0) {
            foreach ($request->get('status') as $color) {

                $colors[] = [
                    'name' => $request->get('name'),
                    'status' => $color,
                    'factory_id' => auth()->user()->factory_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $colors = Color::insert($colors);

        if ($request->ajax()) {
            $color = Color::query()->where('name',$request->get('name'))->first();
            return response()->json([
                'data' => [
                    'id' => $color->id,
                    'text' => $color->name,
                ]
            ]);

            Session::flash('alert-success', 'Data Created Successfully');
        }

        Session::flash('success', 'Data stored successfully');

        return redirect('/colors');
    }

    public function update($name, Request $request)
    {
        $previousColors = Color::query()
            ->where('name', $name)
            ->get();
        $previousStatus = $previousColors->pluck('status');
        $statusRemoved = collect($previousStatus)
            ->diff($request->get('status'));

        Color::query()
            ->whereIn('status', $statusRemoved)
            ->where('name', $name)
            ->delete();

        foreach ($request->get('status') as $status) {
            Color::query()->updateOrCreate(
                [
                    'name' => $name,
                    'status' => $status
                ],
                [
                'name' => $request->get('name'),
                'status' => $status
            ]);
        }

        Session::flash('success', 'Data updated successfully');
        return redirect('/colors');
    }

    public function destroy($id)
    {
        $colorsIdPoItemColorSizeDetails = PoColorSizeBreakdown::query()
            ->get()->pluck('colors')
            ->flatten(1)->unique()
            ->values()->map(function ($item) {
                return (int)$item;
            });

        if (request('restore')) {
            try {
                Color::onlyTrashed()->findOrFail($id)->restore();
                return redirect('/colors')->with('success', 'Data Restore successfully!!');
            } catch (\Exception $exception) {
                return redirect('/colors')->with('error', 'Can Not be Restore!');
            }
        }
        if (!collect($colorsIdPoItemColorSizeDetails)->contains($id)) {
            Color::query()->findOrFail($id)->delete();
            return redirect('/colors')->with('success', 'Data deleted successfully!!');
        } else {
            return redirect('/colors')->with('error', 'Can Not be Deleted ! It is currently associated with Others');
        }
    }

    public function pdfDownload()
    {
        $data['colors'] = Color::with('factory')->orderBy('id', 'DESC')->get();
        $pdf = PDF::loadView('system-settings::pages.colors_pdf', $data);

        return $pdf->download('colors_pdf.pdf');
    }
}
