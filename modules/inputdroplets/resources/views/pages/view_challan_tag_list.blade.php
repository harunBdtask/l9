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
                    <form action="{{ url('/search-challan-or-tag') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            <input type="hidden" name="type"
                                   value="{{ ($title == "Challan List") ? 'challan' : 'tag' }}">
                            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}"
                                   placeholder="{{ ($title == "Challan List") ? 'challan no, floor, line' : 'tag no' }}">
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm white" value="Search">
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
                        @if(isset($challan_list))
                        <th>Appr.</th>
                        @endif
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
                                    $quantity = 0;
                                    $total_rejection = 0;
                                    $print_rejection = 0;
                                    $embr_rejection = 0;
                                    $challan->cutting_inventory->groupBy('bundle_card_id')->each(function($item, $key) use(&$quantity, &$total_rejection, &$print_rejection, &$embr_rejection) {
                                      if ($item->first()->bundlecard) {
                                        $quantity += $item->first()->bundlecard->quantity;
                                        $total_rejection += $item->first()->bundlecard->total_rejection;
                                        $print_rejection += $item->first()->bundlecard->print_rejection;
                                        $embr_rejection += $item->first()->bundlecard->embroidary_rejection;
                                      }
                                    });

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
                                        @if($challan->cut_manager_approval_status)
                                          <i class="fa fa-check-circle-o label-success-md"></i>
                                        @else
                                          <button type="button" class="btn btn-xs btn-warning">
                                              <i class="fa fa-circle-o-notch label-primary-md"></i>
                                          </button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('view-challan-wise-input-bundles/'.$challan->challan_no) }}"
                                           class="btn btn-xs btn-success " data-toggle="tooltip" data-placement="top" title="Challan Bundles">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if(getRole() == 'super-admin' || getRole() == 'admin')
                                         <a href="{{ url('view-challan-wise-deleted-input-bundles/'.$challan->challan_no) }}"
                                           class="btn btn-xs btn-warning " data-toggle="tooltip" data-placement="top" title="Deleted Bundles">
                                            <i class="fa fa-eye-slash"></i>
                                          </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if(getRole() == 'super-admin' || getRole() == 'admin' || session()->has("permission_of_challan_list_bin_card"))
                                          <button class="btn btn-xs btn-info" data-toggle="modal"
                                                  data-id="{{ $challan->id }}" id="addRibDetails"
                                                  data-target="#rib-modal">
                                              <i class="fa fa-plus"></i>
                                          </button>
                                        @endif
                                        <a href="{{ url('view-challan/'.$challan->id) }}"
                                           class="btn btn-xs btn-success " data-toggle="tooltip" data-placement="top" title="View Challan">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ url('view-challan/'.$challan->id.'?version=v2') }}"
                                           class="btn btn-xs btn-info " data-toggle="tooltip" data-placement="top" title="View Challan">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if(getRole() == 'super-admin' || getRole() == 'admin' || session()->has("permission_of_challan_list_bin_card_view"))
                                          <a href="{{ url('view-challan/bin-card/'.$challan->id) }}" data-toggle="tooltip" data-placement="top" title="View Bin Card"
                                            class="btn btn-xs btn-primary ">
                                              <i class="fa fa-eye"></i>
                                          </a>
                                        @endif
                                        @if(getRole() == 'super-admin' || getRole() == 'admin' || session()->has("permission_of_challan_list_edit"))
                                          <a href="{{ url('edit-challan/'.$challan->id) }}" class="btn btn-xs btn-info " data-toggle="tooltip" data-placement="top" title="Edit Challan">
                                              <i class="fa fa-edit"></i>
                                          </a>
                                        @endif
                                        @if(getRole() == 'super-admin' || getRole() == 'admin' || session()->has("permission_of_challan_list_delete"))
                                            <button type="button" class="btn btn-xs btn-danger show-modal"
                                                    data-toggle="modal" data-target="#confirmationModal"
                                                    ui-toggle-class="flip-x" ui-target="#animate"
                                                    data-url="{{ url('delete-challan/'.$challan->id) }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="tr-height">
                                <td colspan="10" class="text-danger text-center">Not found</td>
                            </tr>
                        @endif
                    @elseif(isset($tag_list))
                        @if(!$tag_list->getCollection()->isEmpty())
                            @foreach($tag_list->getCollection() as $tag)
                                <tr class="tr-height">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $tag->challan_no }}</td>
                                    <td>{{ $tag->color->name ?? '' }}</td>
                                    <td>{{ $tag->created_at }}</td>
                                    <td>{{ $tag->user->first_name ." ".$tag->user->last_name }}</td>
                                    <td>
                                        <a href="{{ url('view-tag-wise-bundles/'.$tag->challan_no) }}"
                                           class="btn btn-xs btn-success">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url('view-tag/'.$tag->challan_no) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ url('add-bundle-to-tag?tag-no='.$tag->challan_no) }}"
                                           class="btn btn-xs btn-info ">
                                            Add Bundle
                                        </a>
                                        <a class="btn btn-xs btn-info"
                                           href="{{ url('create-challan-for-sewing/'.$tag->id) }}">Create Challan</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="tr-height">
                                <td colspan="7" class="text-danger text-center">Not found</td>
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

                    @if(isset($tag_list) && $tag_list->total() > 15)
                        <tr>
                            <td colspan="7" align="center">
                                {{ $tag_list->appends(request()->except('page'))->links() }}
                            </td>
                        </tr>
                    @endif
                    </tfoot>
                </table>

            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="rib-modal" tabindex="-1" role="dialog"
             aria-hidden="true" aria-labelledby="exampleModalCenterTitle">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Rib Details
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <form action="/update-challan-rib-details" method="post" id="ribDetailsUpdateForm">
                            @method('patch')
                            @csrf
                            <label>Rib/Piping</label>
                            <div class="row">
                                <div class="col-md-11">
                                    <input class="form-control form-control-sm" name="total_rib_size" type="text">
                                </div>
                                <div class="col-md-1">
                                    <p style="margin-top: 5px"><b>Kg</b></p>
                                </div>
                            </div>
                            <label>Rib Comments</label>
                            <input class="form-control form-control-sm" name="rib_comments" type="text">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="ribDetailsUpdateBtn">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
    </div>
@endsection
@section('scripts')
    <script>
        let editId = null;
        let ribDetailsForm = $("#ribDetailsUpdateForm");

        $(document).on("click", "#addRibDetails", function () {
            editId = $(this).data('id');
            $.ajax({
                url: "/view-challan-rib-details/" + editId,
                type: "get",
                dataType: "JSON",
                success(response) {
                    $('input[name="total_rib_size"]').val(response.total_rib_size);
                    $('input[name="rib_comments"]').val(response.rib_comments);
                }
            });
        })

        $(document).on("click", "#ribDetailsUpdateBtn", function () {
            let url = ribDetailsForm.attr('action');
            url += ("/" + editId);
            let formData = ribDetailsForm.serializeArray();
            $.ajax({
                url: url,
                type: "patch",
                data: formData,
                dataType: "json",
                success(response) {
                    $("#rib-modal").modal("hide");
                    $('input[name="total_rib_size"]').removeAttr("style");
                },
                error(error) {
                    $('input[name="total_rib_size"]').attr("style", "border-color:red");
                }
            })
        })
    </script>
@endsection
