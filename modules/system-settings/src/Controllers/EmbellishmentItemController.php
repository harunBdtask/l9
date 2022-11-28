<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\EmbellishmentItem;
use SkylarkSoft\GoRMG\SystemSettings\Requests\EmbellishmentItemRequest;

class EmbellishmentItemController extends Controller
{
    protected $embl_names;
    protected $printing;
    protected $embroidery;
    protected $special_works;
    protected $gmts_dyeing;
    protected $wash;
    protected $tags;

    public function __construct()
    {
        $this->embl_names = EmbellishmentItem::EMBL_NAMES;
        $this->printing = EmbellishmentItem::PRINTING;
        $this->embroidery = EmbellishmentItem::EMBROIDERY;
        $this->special_works = EmbellishmentItem::SPECIAL_WORKS;
        $this->gmts_dyeing = EmbellishmentItem::GMTS_DYEING;
        $this->wash = EmbellishmentItem::WASH;

        $this->tags = [
            'embellishment_cost' => 'Embellishment Cost',
            'wash_cost' => 'Wash Cost',
        ];
    }

    public function index(Request $request)
    {
        $search_name = $request->search_name ?? '';
        $search_type = $request->search_type ?? '';
        $embellishment_items = EmbellishmentItem::when($search_name != '', function ($query) use ($search_name) {
            return $query->where('name', $search_name);
        })->when($search_type != '', function ($query) use ($search_type) {
            return $query->where('type', $search_type);
        })->orderBy('id', 'desc')->paginate();

        return view('system-settings::embellishment_items.list', [
            'embellishment_items' => $embellishment_items,
            'embellishment_names' => $this->embl_names,
            'embellishment_item' => null,
            'tags' => $this->tags
        ]);
    }

    public function store(EmbellishmentItemRequest $request)
    {
        try {
            DB::beginTransaction();
            $embellishment_item = new EmbellishmentItem();
            $embellishment_item->name = $request->name;
            $embellishment_item->type = $request->type;
            $embellishment_item->tag = $request->tag;
            $embellishment_item->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored successfully!');

            return redirect('/embellishment-items');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $embellishment_item = EmbellishmentItem::findOrFail($id);
            $embellishment_items = EmbellishmentItem::orderBy('id', 'desc')->paginate();

            return view('system-settings::embellishment_items.list', [
                'embellishment_items' => $embellishment_items,
                'embellishment_names' => $this->embl_names,
                'embellishment_item' => $embellishment_item,
                'tags' => $this->tags
            ]);
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect('/embellishment-items');
        }
    }

    public function update($id, EmbellishmentItemRequest $request)
    {
        try {
            DB::beginTransaction();
            $embellishment_item = EmbellishmentItem::findOrFail($id);
            $embellishment_item->name = $request->name;
            $embellishment_item->type = $request->type;
            $embellishment_item->tag = $request->tag;
            $embellishment_item->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated successfully!');

            return redirect('/embellishment-items');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $embellishment_item = EmbellishmentItem::findOrFail($id);
            $embellishment_item->delete();
            DB::commit();
            Session::flash('alert-danger', 'Data Deleted Successfully!!');

            return redirect('/embellishment-items');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!');

            return redirect()->back();
        }
    }
}
