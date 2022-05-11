<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0">
            <li class="breadcrumb-item"><a href="#">{{ get_phrases(['dashboard']) }}</a></li>
            <li class="breadcrumb-item active">{{$title}}</li>
        </ol>
    </nav>
    <div class="col-sm-8 header-title p-0">
        <div class="media">
            <div class="header-icon text-success"><i class="typcn typcn-puzzle-outline"></i></div>
            <div class="media-body">
                <h1 class="font-weight-bold">{{$title}}</h1>
                <small>{{$title}}</small>
            </div>
        </div>
    </div>
</div>
<!--/.Content Header (Page header)-->
<div class="body-content">
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 font-weight-600 mb-0">{{$title}}</h6>
                </div>
                <div class="text-right">
                    <div class="actions">
                        <a href="#" class="action-item reload"><i class="ti-reload"></i></a>
                        <div class="dropdown action-item" data-toggle="dropdown">
                            <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item reload">{{ get_phrases(['refresh']) }}</a>
                                <a href="#" class="dropdown-item">{{ get_phrases(['manage','widgets']) }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-body shadow-none mb-4">
                        <div id="html" class="demo">
                            {!! $trees !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <form>
                        <input type="hidden" name="HeadName" id="HeadName" class="form-control" required="required" />
                        <div id="newData">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">

                                <tr>
                                    <td>{{ get_phrases(['directory', 'name']) }}</td>
                                    <td><input type="text" name="txtHeadName" id="txtHeadName" class="form-control" /></td>
                                </tr>
                                <tr>
                                    <td>{{ get_phrases(['parent', 'directory']) }}</td>
                                    <td><input type="text" name="txtPHead" id="txtPHead" class="form-control" readonly="readonly" /></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary actionBtn">{{ get_phrases(['create']) }}</button>
                                        <button class="btn btn-success actionBtn2">{{ get_phrases(['rename']) }}</button>
                                        <button class="btn btn-danger actionBtn3">{{ get_phrases(['delete']) }}</button>
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header py-2">

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <nav aria-label="breadcrumb" class="order-sm-last p-0">
                                        <ol class="breadcrumb d-inline-flex font-weight-600 fs-17 bg-white mb-0 float-sm-left p-0">
                                            <li class="breadcrumb-item"><a href="#">{{ get_phrases(['file','list']) }}</a></li>
                                            <li class="breadcrumb-item active">{{ get_phrases(['table']) }}</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-success btn-sm mr-1 addShowModal"><i class="fas fa-plus mr-1"></i>{{ get_phrases(['upload', 'file']) }}</button>
                                </div>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="row form-group">
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endif
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <table id="itemsList" class="table display table-bordered table-striped table-hover compact" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">{{ get_phrases(['sl']) }}</th>
                                        <th width="10%">{{ get_phrases(['directory']) }}</th>
                                        <th width="10%">{{ get_phrases(['file','name']) }}</th>
                                        <th width="10%">{{ get_phrases(['image', 'preview']) }}</th>
                                        <th width="5%">{{ get_phrases(['download']) }}</th>
                                        <th width="5%">{{ get_phrases(['action']) }}</th>
                                        <th width="0%"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->
<!-- modal button -->
<div class="modal fade bd-example-modal-xl" id="items-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemsModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form enctype="multipart/form-data" action="{{route('upload_file')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row form-group">
                        <label for="directory" class="col-sm-3 col-form-label font-weight-600">{{ get_phrases(['select', 'directory']) }} </label>
                        <select name="directory" id="directory" class="form-control col-sm-6" >
                            <option>directory</option>
                            @foreach ($all_directories as $value)
                                <option>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row form-group">
                        <label for="attc" class="col-sm-3 col-form-label font-weight-600">{{ get_phrases(['file']) }} </label>
                        <div id='attc' class="col-sm-6">
                            <div class="input-group" style="margin-top:5px;">
                                <input type="file" name="attc[]" class="form-control" />
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-success addRow"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-danger removeRow"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ get_phrases(['close']) }}</button>
                    <button type="submit" class="btn btn-success modal_action"></button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadData(id) {
        $('#txtPHead').val(id);
    }

    function actionAjax(action) {
        var txtPHead = $('#txtPHead').val();
        var txtHeadName = $('#txtHeadName').val();
        if (txtPHead != '') {
            if (action != 'delete') {
                if (txtHeadName == '') {
                    alert('Invalid directory name !!');
                    return;
                }
            }
            if (action != 'create') {
                if (txtPHead == 'directory') {
                    alert('Invalid Attempt !! ');
                    return;
                }
            }
            $.ajax({
                url: "{{ route('project.store') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'txtPHead': txtPHead,
                    'txtHeadName': txtHeadName,
                    'action': action,
                },
                success: function(data) {
                    location.reload();
                }
            });
        } else {
            alert('Invalid Attempt !! Select directory');
            return;
        }
    }


    $('document').ready(function() {
        "use strict";

        //create
        $('.actionBtn').click(function(e) {
            e.preventDefault();
            var txtPHead = $('#txtPHead').val();
            var nameArr = txtPHead.split('/');
            if (nameArr.length > 4) {
                alert('Max level directory exceeded !!');
                return;
            }
            actionAjax('create');
        });

        //reload
        $('.reload').click(function(e) {
            e.preventDefault();
            location.reload();
        });

        //delete
        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('remove_file') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    '_token': "{{ csrf_token() }}",
                    'directory': id,
                },
                success: function(data) {
                    $('#itemsList').DataTable().ajax.reload(null, false);
                }
            });

        });

        //rename
        $('.actionBtn2').click(function(e) {
            e.preventDefault();
            actionAjax('rename');
        });

        //delete
        $('.actionBtn3').click(function(e) {
            e.preventDefault();
            swal({
                title: "Delete?",
                text: "Please ensure and then confirm!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                reverseButtons: !0
            }).then(function(e) {
                if (e.value === true) {
                    actionAjax('delete');
                } else {
                    e.dismiss;
                }
            }, function(dismiss) {
                return false;
            })
        });


        $('body').on('click', '.addRow', function() {
            var html = '<div class="input-group" style="margin-top:5px;">' +
                '<input type="file" name="attc[]" class="form-control" />' +
                '<div class="input-group-prepend">' +
                '<button type="button" class="btn btn-success addRow" ><i class="fa fa-plus"></i></button>' +
                '<button type="button" class="btn btn-danger removeRow" ><i class="fa fa-minus"></i></button>' +
                '</div>' +
                '</div>';
            $("#attc").append(html);
        });

        $('body').on('click', '.removeRow', function() {
            var rowCount = $('#attc >div').length;
            if (rowCount > 1) {
                $(this).parent().parent().remove();
            } else {
                alert('There only one row, you can not delete !! ');
            }
        });

        //add files
        $('.addShowModal').on('click', function() {
            $('#itemsModalLabel').text("{{ get_phrases(['add', 'file']) }}");
            $('.modal_action').text("{{ get_phrases(['add']) }}");
            $('#items-modal').modal('show');
        });

        // table serverside
        var table = $('#itemsList').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('project.index') }}",
            columns: [
                {data: 'id'},
                {data: 'directory'},
                {
                    data: 'name'
                },
                {
                    data: "image",
                    render: function(data, type, row, meta) {
                        return '<img src="' + data + '" alt="file" class="img-thumbnail" height="70" width="70">';
                    }
                },
                {
                    data: "download",
                    render: function(data, type, row, meta) {
                        return '<a href="' + data + '" target="_blank" rel="noopener noreferrer" class="btn btn-primary"><i class="fa fa-download"></i> </a>';
                    }
                },
                {
                    data: "button",
                    render: function(data, type, row, meta) {
                        return '<a href="javascript:void(0)" data-id="' + data + '" class="delete btn btn-danger btn-sm">Delete</a>';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });

    });
</script>
@endpush