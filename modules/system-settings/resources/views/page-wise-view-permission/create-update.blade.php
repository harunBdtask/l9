@extends('skeleton::layout')
@section('title','Page Wise View Permissions')
@push('style')
    <style>
        .select-option {
            min-height: 2.375rem !important;
        }

        .custom-input {
            width: 200px;
        }

        table thead tr th {
            white-space: nowrap;
        }

        .select2-selection--single {
            border-radius: 0px !important;
            border: 1px solid #e7e7e7 !important;
        }
    </style>
@endpush
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Page Wise View Permission</h2>
            </div>
            <div class="box-divider m-a-0"></div>
            <div class="box-body b-t ">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                <div class="" id="page_wise_view_permission_form">
                    <form action="{{ url('page-wise-view-permission') }}" method="post">
                        @csrf
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <td>Company</td>
                                <td>User</td>
                                <td>Select Page</td>
                                <td>Select Print</td>
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 10%">
                                    <select name="company_id" style="height: 40px; width: 200px;" id="company_id"
                                            class="form-control form-control-sm select2-input">

                                        @foreach($companies as $key => $company)
                                            <option
                                                value="{{ $key }}" {{  request()->company_id ? 'selected' : null }}>{{ $company }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="max-width: 25%">
                                    <select name="user_id[]" style="height: 40px;"
                                            class="form-control form-control-sm"
                                            multiple id="user_id" data-select="true">
                                        <option value="All">
                                            Select All
                                        </option>

                                    </select>
                                </td>
                                <td style="width: 25%">
                                    <select name="page_id[]" style="height: 40px; width: 200px;" multiple
                                            class="form-control form-control-sm select2-input" id="page_id">
                                        @foreach($pages as $pageKey => $page)
                                            <option value="{{$pageKey}}">{{$page}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 30%">
                                    <select name="view_id[]" style="height: 40px; width: 200px;" multiple
                                            class="form-control form-control-sm " id="view_id">
                                    </select>
                                </td>
                                <td style="width: 5%">
                                    <button class="btn btn-xs btn-success">Save</button>
                                </td>
                            </tr>
                            
                            </tbody>
                        </table>
                        </form>
                        <form action="{{ url('page-wise-view-permission') }}" method="get">
                        @csrf

                        <table class="reportTable" style="width: 30%">                        
                            <tr style="width: 30%" >
                                <tr>
                                    <th>Search By user</th>
                                    <th>Action</th>
                                </tr>
                                <td >
                                    <select name="user_search_id[]" 
                                                class="form-control form-control-sm"
                                                multiple id="user_search_id" data-select="true">
                                            <option value="All">
                                                Select All
                                            </option>

                                        </select>
                                       
                                </td>
                                <td> <button class="btn btn-xs btn-success">Search</button></td>                                
                            </tr>
                        </table>
                    </form>
                </div>
                <hr>
            </div>

            @if(!$permissions->isEmpty())
                <div class="box-body b-t">
                    <table class="table reportTable">
                        <thead>
                        <tr>
                            <th>SL</th>
                            <th>Company Name</th>
                            <th>User</th>
                            <th>Page Name</th>
                            <th>View Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($permissions as $key => $permission)
                            <tr>
                                {{--                                {{ dd($permission) }}--}}
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ($permission->first()->company->factory_name) ?? '' }}</td>
                                <td>{{ $permission->first()->user->email ?? '' }}</td>
                                <td>
                                    @foreach($permission->unique('page_id') as $page)
                                        <button
                                            style="min-width: 50px;background-color: #31b0d5;border-color: #2aabd2;font-size: 10px;"
                                            type="button"
                                            class="label label-info m-r-1 font-12 margin-2 show-modal btn btn-xs btn-info"
                                            data-toggle="modal" data-target="#confirmationModal"
                                            ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('page-wise-view-permission/delete-page/'.$page->user_id.'/'.$page->page_id) }}">
                                            {{ $page->page }}
                                            <i style="color: rgb(193 5 5);cursor: pointer"
                                               class="fa fa-times"></i>
                                        </button>
                                    @endforeach

                                </td>
                                <td>
                                    @foreach($permission as $page)
                                        <button
                                            style="min-width: 50px;background-color: #31b0d5;border-color: #2aabd2;font-size: 10px;"
                                            type="button"
                                            class="label label-info m-r-1 font-12 margin-2 show-modal btn btn-xs btn-info"
                                            data-toggle="modal" data-target="#confirmationModal"
                                            ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('page-wise-view-permission/delete-view/'.$page->user_id.'/'.$page->view_id) }}">
                                            {{ $page->view }}
                                            <i style="color: rgb(193 5 5);cursor: pointer"
                                               class="fa fa-times"></i>
                                        </button>
                                    @endforeach
                                </td>
                                <td>

                                    <button type="button" class="btn btn-xs danger show-modal"
                                            data-toggle="modal" data-target="#confirmationModal"
                                            ui-toggle-class="flip-x" ui-target="#animate"
                                            data-url="{{ url('page-wise-view-permission/'.$permission->first()['user_id']) }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data Found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                </div>

            @endif
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(document).ready(function () {
            let company = $("#company_id");
            let user = $('#user_id');
            let userSearch = $('#user_search_id');
            let page = $('#page_id');
            let view = $('#view_id');
            fetchCompanyWiseUser();
            fetchPageWiseView();

            company.change(function () {
                fetchCompanyWiseUser();
            });

            page.change(function () {
                console.log("ASDF");
                fetchPageWiseView();
            });

            function fetchCompanyWiseUser() {
                let companyId = company.val();
                $.ajax({
                    url: `/get-users/${companyId}`,
                    type: "get",
                    dataType: "json",
                    success(response) {
                        let options = null;
                        if (response.length) {
                            options = '<option value="all">ALL USER</option>';
                        }
                        response.forEach(function (user) {
                            options += `<option value="${user.id}">${user.email}</option>`;
                        })
                        user.html(options);
                        userSearch.html(options);
                        user.select2();
                        userSearch.select2();
                        let str = "{{implode(',',$search)}}" 
                        let arr = str.split(',')
                        userSearch.select2('val',arr);                        
                    }
                });
            }

            function fetchPageWiseView() {
                let pages = page.val();
                $.ajax({
                    url: `page-wise-view-permission/get-views`,
                    type: 'GET',
                    data: {'pages': pages},
                    context: this,
                    success(response) {
                        console.log(response);
                        let options = null;
                        response.forEach(function (value) {
                            options += `<option value="${value.id}">${value.name}</option>`;
                        })
                        view.html(options);
                        view.select2();
                    }
                });
            }
        });
    </script>
@endpush
