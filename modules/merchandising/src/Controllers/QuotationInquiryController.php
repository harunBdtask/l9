<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Session;
use SkylarkSoft\GoRMG\Merchandising\Models\QuotationInquiry;
use SkylarkSoft\GoRMG\Merchandising\Models\QuotationInquiryDetail;
use SkylarkSoft\GoRMG\Merchandising\Requests\QuotationInquiryRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class QuotationInquiryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? null;
        $factory = $request->search_column == 'factory' ? $request->search_column : null;
        $quotation_id = $request->search_column == 'quotation_id' ? $request->search_column : null;
        $buyer = $request->search_column == 'buyer' ? $request->search_column : null;
        $buyer_id = ($buyer && $q) ? (Buyer::where('name', $q)->first() ? Buyer::where('name', $q)->first()->id : null) : null;
        $style_name = $request->search_column == 'style_name' ? $request->search_column : null;
        $season = $request->search_column == 'season' ? $request->search_column : null;
        $season_id = ($season && $q) ? (Season::where('season_name', $q)->first() ? Season::where('season_name', $q)->pluck('id')->toArray() : null) : null;
        $inquiry_date = $request->search_column == 'inquiry_date' ? $request->search_column : null;
        $year = $request->search_column == 'year' ? $request->search_column : null;
        $sort = request('sort') ?? 'desc';
        $quotation_inquiries = QuotationInquiry::orderBy('id', $sort)
            ->when($factory, function ($query) use ($q) {
                $query->whereHas('factory', function($query) use($q){
                    $query->where('factory_name', 'LIKE', "%{$q}%")
                    ->orWhere('factory_short_name','LIKE', "%{$q}%");
                });
            })->when(($buyer), function ($query) use ($q) {
                 $query->whereHas('buyer', function($query) use($q){
                     $query->where('name','LIKE',"%{$q}%");
                 });
            })->when(($season), function ($query) use ($q) {
                $query->whereHas('season', function($query) use($q){
                    $query->where('season_name','LIKE',"%{$q}%");
                });
            })->when(($style_name && $q), function ($query) use ($q) {
                return $query->where('style_name', $q);
            })->when(($quotation_id && $q), function ($query) use ($q) {
                return $query->where('quotation_id', $q);
            })->when(($year && $q), function ($query) use ($q) {
                return $query->whereYear('created_at', $q);
            })->when(($inquiry_date && $q), function ($query) use ($q) {
                return $query->whereDate('inquiry_date', date('Y-m-d', strtotime($q)));
            })
            ->paginate();
        $search_columns = [
            'factory' => "Factory",
            'quotation_id' => "Inquiry Id",
            'buyer' => "Buyer",
            'style_name' => "Style",
            'season' => "Season",
            'inquiry_date' => "Inquiry Date",
            'year' => "Year",
        ];

        return view('merchandising::quotation_inquiry.list', [
            'quotation_inquiries' => $quotation_inquiries,
            'search_columns' => $search_columns,
        ]);
    }

    public function create()
    {
        $quotation_inquiry = null;
        $quotation_id_prefix = "QI" . date('Ymd');
        $quotation_id_suffix = Str::random(5);

        $quotation_id = $quotation_id_prefix . '-' . $quotation_id_suffix;
        $factories = Factory::all()->pluck('factory_name', 'id');
        $buyers = Buyer::all()->pluck('name', 'id');
        $garment_items = GarmentsItem::all()->pluck('name', 'id');
        $seasons = Season::all()->pluck('season_name', 'id');
        $required_samples = QuotationInquiry::REQUIRED_SAMPLE;
        $status = QuotationInquiry::STATUS;
        $team_members = Team::all()->pluck('member_id');
        $dealing_merchants = User::query()->whereIn('id', $team_members)->pluck('screen_name', 'id');

        return view('merchandising::quotation_inquiry.create_update', [
            'quotation_id' => $quotation_id,
            'factories' => $factories,
            'buyers' => $buyers,
            'garment_items' => $garment_items,
            'seasons' => $seasons,
            'dealing_merchants' => $dealing_merchants,
            'required_samples' => $required_samples,
            'status' => $status,
            'quotation_inquiry' => $quotation_inquiry,
        ]);
    }

    public function store(QuotationInquiryRequest $request)
    {
        try {
            DB::beginTransaction();
            $quotation_inquiry = new QuotationInquiry();
            $quotation_inquiry->quotation_id = $request->quotation_id;
            $quotation_inquiry->factory_id = $request->factory_id;
            $quotation_inquiry->buyer_id = $request->buyer_id;
            $quotation_inquiry->style_name = $request->style_name;
            $quotation_inquiry->style_description = $request->style_description;
            $quotation_inquiry->garment_item_id = $request->garment_item_id;
            $quotation_inquiry->season_id = $request->season_id;
            $quotation_inquiry->status = $request->status;
            $quotation_inquiry->inquiry_date = $request->inquiry_date;
            $quotation_inquiry->dealing_merchant = $request->dealing_merchant;
            $quotation_inquiry->submission_date = $request->submission_date;
            $quotation_inquiry->approval_date = $request->approval_date;
            $quotation_inquiry->required_sample = $request->required_sample;
            $quotation_inquiry->remarks = $request->remarks;
            if ($request->hasFile('file_name')) {
                $time = time();
                $file = $request->file('file_name');
                $file->storeAs('quotation_inquiry', $time . $file->getClientOriginalName());
                $quotation_inquiry->file_name = $time . $file->getClientOriginalName();
            }

            $quotation_inquiry->save();

            $quotation_id = $quotation_inquiry->quotation_id;
            if ($request->has('fabrication')) {
                foreach ($request->fabrication as $key => $val) {
                    $quotation_inquiry_detail_id = $request->quotation_details_id[$key];
                    $quotation_inquiry_detail = QuotationInquiryDetail::findOrNew($quotation_inquiry_detail_id);
                    $quotation_inquiry_detail->quotation_id = $quotation_id;
                    $quotation_inquiry_detail->fabrication = $val;
                    $quotation_inquiry_detail->gsm = $request->gsm[$key];
                    $quotation_inquiry_detail->fabric_composition = $request->fabric_composition[$key];
                    $quotation_inquiry_detail->save();
                }
            }
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('quotation-inquiries');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $quotation_inquiry = QuotationInquiry::findOrFail($id);
        $factories = Factory::all()->pluck('factory_name', 'id');
        $buyers = Buyer::all()->pluck('name', 'id');
        $garment_items = GarmentsItem::all()->pluck('name', 'id');
        $seasons = Season::all()->pluck('season_name', 'id');
        $required_samples = QuotationInquiry::REQUIRED_SAMPLE;
        $status = QuotationInquiry::STATUS;
        $team_members = Team::all()->pluck('member_id');
        $dealing_merchants = User::whereIn('id', $team_members)->pluck('email', 'id');


        return view('merchandising::quotation_inquiry.create_update', [
            'factories' => $factories,
            'buyers' => $buyers,
            'garment_items' => $garment_items,
            'seasons' => $seasons,
            'dealing_merchants' => $dealing_merchants,
            'required_samples' => $required_samples,
            'status' => $status,
            'quotation_inquiry' => $quotation_inquiry,
        ]);
    }

    public function update($id, QuotationInquiryRequest $request)
    {
        try {
            DB::beginTransaction();
            $quotation_inquiry = QuotationInquiry::findOrFail($id);
            $quotation_inquiry->quotation_id = $request->quotation_id;
            $quotation_inquiry->factory_id = $request->factory_id;
            $quotation_inquiry->buyer_id = $request->buyer_id;
            $quotation_inquiry->style_name = $request->style_name;
            $quotation_inquiry->style_description = $request->style_description;
            $quotation_inquiry->garment_item_id = $request->garment_item_id;
            $quotation_inquiry->season_id = $request->season_id;
            $quotation_inquiry->status = $request->status;
            $quotation_inquiry->inquiry_date = $request->inquiry_date;
            $quotation_inquiry->dealing_merchant = $request->dealing_merchant;
            $quotation_inquiry->submission_date = $request->submission_date;
            $quotation_inquiry->approval_date = $request->approval_date;
            $quotation_inquiry->required_sample = $request->required_sample;
            $quotation_inquiry->remarks = $request->remarks;
            if ($request->hasFile('file_name')) {
                $quotation_inquiry_data = QuotationInquiry::where('id', $id)->first();
                if (isset($quotation_inquiry_data->file_name)) {
                    $file_name_to_delete = $quotation_inquiry_data->file_name;
                    if (Storage::disk('public')->exists('/quotation_inquiry/' . $file_name_to_delete)) {
                        if ($file_name_to_delete != null) {
                            Storage::delete('quotation_inquiry/' . $file_name_to_delete);
                        }
                    }
                }
                $time = time();
                $file = $request->file_name;
                $file->storeAs('quotation_inquiry', $time . $file->getClientOriginalName());
                $quotation_inquiry->file_name = $time . $file->getClientOriginalName();
            }


            $quotation_inquiry->save();

            $quotation_id = $quotation_inquiry->quotation_id;
            if ($request->has('fabrication')) {
                foreach ($request->fabrication as $key => $val) {
                    $quotation_inquiry_detail_id = $request->quotation_details_id[$key];
                    $quotation_inquiry_detail = QuotationInquiryDetail::findOrNew($quotation_inquiry_detail_id);
                    $quotation_inquiry_detail->quotation_id = $quotation_id;
                    $quotation_inquiry_detail->fabrication = $val;
                    $quotation_inquiry_detail->gsm = $request->gsm[$key];
                    $quotation_inquiry_detail->fabric_composition = $request->fabric_composition[$key];
                    $quotation_inquiry_detail->save();
                }
            }
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('quotation-inquiries');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $quotation_inquiry = QuotationInquiry::findOrFail($id);
            $quotation_inquiry->delete();
            DB::commit();
            Session::flash('alert-success', 'Data Deleted Successfully!!');

            return redirect('quotation-inquiries');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroyDetails($id)
    {
        try {
            DB::beginTransaction();
            $quotation_inquiry_detail = QuotationInquiryDetail::findOrFail($id);
            $quotation_inquiry_detail->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'errors' => null,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
            ]);
        }
    }

    public function getQuotationInquiryDetails(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404);
        }

        try {
            $id = $request->quotation_inquiry_id;
            $quotation_inquiry = QuotationInquiry::findOrFail($id);
            $inquiry_data = [
                'factory_id' => $quotation_inquiry->factory_id ?? '',
                'location' => $quotation_inquiry->factory_id ? $quotation_inquiry->factory->factory_address : '',
                'buyer_id' => $quotation_inquiry->buyer_id ?? '',
                'style_name' => $quotation_inquiry->style_name ?? '',
                'style_description' => $quotation_inquiry->style_description ?? '',
                'season_id' => $quotation_inquiry->season_id ?? '',
            ];

            return response()->json([
                'status' => 'success',
                'inquiry_data' => $inquiry_data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'danger',
                'errors' => $e->getMessage(),
            ]);
        }
    }
}
