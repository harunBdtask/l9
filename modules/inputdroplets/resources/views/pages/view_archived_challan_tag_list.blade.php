@extends('inputdroplets::layout')
@section('title', $title ?? 'Challan/Tag')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>{{ $title }}</h2>
            </div>
            <div class="box-body b-t">
                @include('partials.response-message')
                <div class="pull-right" style="margin-bottom: 10px;">
                    <form action="{{ url('/search-archived-challan-or-tag') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="hidden" name="type"
                                   value="{{ ($title == "Challan List (Archived)") ? 'challan' : 'tag' }}">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}"
                                   placeholder="{{ ($title == "Challan List (Archived)") ? 'challan no, floor, line' : 'tag no' }}">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm btn-info" value="Search">
                        </div>
                    </form>
                </div>

                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        @if(isset($challan_list))
                            <th>Challan No.</th>
                            <th>Floor No.</th>
                            <th>Line No.</th>
                        @else
                            <th>Tag No.</th>
                        @endif
                        <th>Color</th>
                        @if(isset($challan_list))
                            <th>Challan Qty</th>
                        @endif
                        <th>Date Time</th>
                        <th>Created By</th>
                        <th>View Bundles</th>
                        <th width="10%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($challan_list))
                        @if(!$challan_list->getCollection()->isEmpty())
                            @foreach($challan_list->getCollection() as $challan)
                                @php
                                    $challan_originial_time = $challan->updated_at;
                                    $new_challan_time = date('Y-m-d', strtotime($challan_originial_time)).' ';
                                      if (date('H', strtotime($challan_originial_time)) < 8) {
                                        $new_challan_time .= '08:'.date('i:s', strtotime($challan_originial_time));
                                      } elseif (date('H', strtotime($challan_originial_time)) >= 19) {
                                        $new_challan_time .= '18:'.date('i:s', strtotime($challan_originial_time));
                                      } else {
                                        $new_challan_time .= date('H:i:s', strtotime($challan_originial_time));
                                      }
                                    $archived_cutting_inventory = $challan->archived_cutting_inventory;
                                    $cutting_inventory_clone = clone $archived_cutting_inventory;
                                    $quantity = $cutting_inventory_clone->sum('archivedBundlecard.quantity');
                                    $total_rejection = $cutting_inventory_clone->sum('archivedBundlecard.total_rejection');
                                    $print_rejection = $cutting_inventory_clone->sum('archivedBundlecard.print_rejection');
                                    $embr_rejection = $cutting_inventory_clone->sum('archivedBundlecard.embroidary_rejection');
                                    $print_embroidary_rejection = $print_rejection >= $embr_rejection ? $print_rejection : $embr_rejection;

                                    $input_qty = $quantity - $total_rejection - $print_embroidary_rejection;
                                @endphp
                                <tr class="tr-height">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $challan->challan_no }}</td>
                                    <td>{{ $challan->line->floor->floor_no ?? '' }}</td>
                                    <td>{{ $challan->line->line_no ?? '' }}</td>
                                    <td>{{ $challan->color->name ?? '' }}</td>
                                    <td>{{ $input_qty }}</td>
                                    <td>{{ $new_challan_time }}</td>
                                    <td>{{ $challan->user->first_name ." ".$challan->user->last_name }}</td>
                                    <td>
                                        <a href="{{ url('view-archived-challan-wise-input-bundles/'.$challan->challan_no) }}"
                                           class="btn btn-xs btn-success ">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ url('view-archived-challan/'.$challan->id) }}"
                                           class="btn btn-xs btn-success ">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="tr-height">
                                <td colspan="10" class="text-danger text-center">Not found</td>
                            </tr>
                        @endif
                    @endif
                    </tbody>
                    <tfoot>
                    @if(isset($challan_list) && $challan_list->total() > 15)
                        <tr>
                            <td colspan="10" align="center">
                                {{ $challan_list->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
@endsection