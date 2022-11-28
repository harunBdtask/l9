@extends('skeleton::layout')
@section("title","Items")
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_lien_bank_view') || getRole() == 'super-admin' || getRole() == 'admin')
            <div class="box">
                <div class="box-header">
                    <h2>Lien Bank List</h2>
                </div>
                <div class="box-body b-t">
                    <div class="row">
                        <div class="col-sm-3 col-sm-offset-9">
                            <form action="{{ url('lien-banks') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm" name="search"
                                           value="{{ $search ?? '' }}" placeholder="Search">
                                    <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            @include('partials.response-message')
                        </div>
                    </div>
                    <div class="row m-t">
                        @if(Session::has('permission_of_lien_bank_add') || getRole() == 'super-admin' || getRole() == 'admin')
                            <div class="col-sm-12 col-md-5">
                                <div class="box form-colors">
                                    <div class="box-header">
                                        <form action="{{ url('/lien-banks') }}" method="post" id="form">
                                            @csrf
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">Name</label>
                                                            <input type="text" name="name" id="name"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ old('name') }}" placeholder="Name" required>
                                                            @error('name')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="contact_person">Contact Person</label>
                                                            <input type="text" name="contact_person" id="contact_person"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ old('contact_person') }}"
                                                                   placeholder="Contact Persion">
                                                            @error('contact_person')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                    </div>
                                                </div>

                                               <div class="row">
                                                   <div class="col-sm-12 col-md-6">
                                                       <div class="form-group">
                                                           <label for="swift_code">Swift Code</label>
                                                           <input type="text" name="swift_code" id="swift_code"
                                                                  class="form-control form-control-sm"
                                                                  value="{{ old('swift_code') }}"
                                                                  placeholder="Contact Persion">
                                                           @error('swift_code')
                                                           <div class="alert alert-danger">{{ $message }}</div>
                                                           @enderror
                                                       </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12">
                                                       <div class="form-group">
                                                           <label for="addressLienBank">Address</label>
                                                           <!-- {!! Form::textarea('address', null, ['id' => 'address', 'placeholder' => '', 'rows' => 2, 'required' => 'required']) !!} -->
                                                           <textarea rows="3" cols="25" name="address" id="addressLienBank"
                                                                  class="form-control form-control-sm"
                                                                  value="{{ old('address') }}" placeholder="Address" required>
                                                           </textarea>
                                                           @error('address')
                                                           <div class="alert alert-danger">{{ $message }}</div>
                                                           @enderror
                                                       </div>
                                                   </div>
                                                   <div class="col-sm-12">
                                                       <div class="form-group">
                                                           <button type="submit" id="submit" class="btn btn-sm btn-success">
                                                               <i class="fa fa-save"></i> Create
                                                           </button>
                                                           <a href="{{ url('lien-banks') }}" class="btn btn-sm btn-warning">
                                                               <i class="fa fa-refresh"></i> Refresh
                                                           </a>
                                                       </div>
                                                   </div>
                                               </div>


                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-12 col-md-7">
                            <table class="reportTable display compact cell-border" id="item_list_table">
                                <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Contact Person</th>
                                    <th>Swift Code</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ PAD($loop->iteration) }}</td>
                                        <td class="text-left"
                                            style="padding-left: 5px !important;">{{ $item->name ?? 'N/A' }}</td>
                                        <td class="text-left"
                                            style="padding-left: 5px !important;">{!! $item->address ?? 'N/A' !!}</td>
                                        <td class="text-left"
                                            style="padding-left: 5px !important;">{{ $item->contact_person ?? 'N/A' }}</td>
                                        <td class="text-left"
                                            style="padding-left: 5px !important;">{{ $item->swift_code ?? 'N/A' }}</td>
                                        <td style="padding: 2px">
                                            @if(Session::has('permission_of_lien_bank_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <a class="btn btn-xs btn-success edit" data-id="{{ $item->id }}"
                                                   href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                                            @endif
                                            @if(Session::has('permission_of_lien_bank_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                                <button type="button" class="btn btn-xs btn-danger show-modal"
                                                        data-toggle="modal" data-target="#confirmationModal"
                                                        ui-toggle-class="flip-x" ui-target="#animate"
                                                        data-url="{{ url('lien-banks/'.$item->id ) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">No Data Found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="text-center">
                                {{ $items->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('click', '.edit', function () {
            let id = $(this).data('id');
            $.ajax({
                method: 'get',
                url: '{{ url('lien-banks') }}/' + id + '/edit',
                success: function (result) {
                    $('#form').attr('action', `lien-banks/${result.id}`).append(`<input type="hidden" name="_method" value="PUT"/>`);
                    $('#name').val(result.name);
                    CKEDITOR.instances['addressLienBank'].setData(result.address);
                    $('#swift_code').val(result.swift_code);
                    $('#contact_person').val(result.contact_person);
                    $('#submit').html(`<i class="fa fa-save"></i> Update`);
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        })
    </script>
@endpush
