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
                <div class="col-sm-5">
                    <div class="card card-body shadow-none mb-4">
                        <div id="html" class="demo">
                            {!! $trees !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <form>
                        <div>
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
                                        <button class="btn btn-primary btn-sm actionBtn">{{ get_phrases(['create']) }}</button>
                                        <button class="btn btn-success btn-sm actionBtn2">{{ get_phrases(['rename']) }}</button>
                                        <button class="btn btn-danger btn-sm actionBtn3">{{ get_phrases(['delete']) }}</button>
                                        <button type="button" class="btn btn-info btn-sm actionBtn4">{{ get_phrases(['copy']) }}</button>
                                        <button type="button" class="btn btn-warning btn-sm actionBtn5">{{ get_phrases(['move']) }}</button>
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
                                        <th width="10%">{{ get_phrases(['action']) }}</th>
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
<!-- modal button -->
<div class="modal fade bd-example-modal-xl" id="items-modal-two" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-600" id="itemsModalLabelTwo"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                @csrf
                <input type="hidden" name="dir_src" id="dir_src">
                <input type="hidden" name="dir_action" id="dir_action">
                <div class="modal-body">
                    <div class="row form-group">
                        <label for="directory" id="dir_label" class="col-sm-3 col-form-label font-weight-600"> </label>
                        <select name="directory" id="dir" class="form-control col-sm-6" required>
                            <option>directory</option>
                            @foreach ($all_directories as $value)
                                <option>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ get_phrases(['close']) }}</button>
                    <button type="submit" class="btn btn-success modal_action_two"></button>
                    <button type="submit" class="btn btn-primary modal_action_two_file"></button>
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
        if (txtPHead != '') {
            if (action == 'copy' || action == 'move') {
                var txtHeadName = 'na';
                var dir = $('#dir').val();
                if (dir == null) {
                    alert('Select Directory !! ');
                    return;
                }
            }else{
                var dir = 'no';
                var txtHeadName = $('#txtHeadName').val();
            }
            if (action == 'create' || action == 'rename') {
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
                    'dir': dir,
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

    
    function fileAction(id, action, destination) {
        if (action != 'delete' && destination == null) {
            alert('Invalid Attempt !! Select directory');
            return;
        }
        $.ajax({
            url: "{{ route('file_action') }}",
            type: "POST",
            dataType: 'json',
            data: {
                '_token': "{{ csrf_token() }}",
                'directory': id,
                'action': action,
                'destination': destination,
            },
            success: function(data) {
                $('#items-modal-two').modal('hide');
                $('#itemsList').DataTable().ajax.reload(null, false);
            }
        });
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
        
        //fileCopy
        $('body').on('click', '.fileCopy', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#dir_src').val(id);
            $('#dir').val('').trigger('change');
            $('#dir_action').val('fileCopy');
            $('#itemsModalLabelTwo').text("{{ get_phrases(['copy', 'file']) }}");
            $('#dir_label').text("{{ get_phrases(['copy', 'to', 'directory']) }}");
            $('.modal_action_two').hide();
            $('.modal_action_two_file').show();
            $('.modal_action_two_file').text("{{ get_phrases(['copy']) }}");
            $('#items-modal-two').modal('show');
        });

        //fileMove
        $('body').on('click', '.fileMove', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#dir_src').val(id);
            $('#dir').val('').trigger('change');
            $('#dir_action').val('fileMove');
            $('#itemsModalLabelTwo').text("{{ get_phrases(['move', 'file']) }}");
            $('#dir_label').text("{{ get_phrases(['move', 'to', 'directory']) }}");
            $('.modal_action_two').hide();
            $('.modal_action_two_file').show();
            $('.modal_action_two_file').text("{{ get_phrases(['move']) }}");
            $('#items-modal-two').modal('show');
        });

        //delete
        $('body').on('click', '.delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            fileAction(id, 'delete', null)
        });
        
        //file copy action
        $('.modal_action_two_file').click(function(e) {
            e.preventDefault();
            var dir_src = $('#dir_src').val();
            var action = $('#dir_action').val();
            var dir_dest = $('#dir').val();
            fileAction(dir_src, action, dir_dest);
        });


        //rename
        $('.actionBtn2').click(function(e) {
            e.preventDefault();
            actionAjax('rename');
        });

        //copy action
        $('.modal_action_two').click(function(e) {
            e.preventDefault();
            var action = $('#dir_action').val();
            actionAjax(action);
        });

        //copy
        $('.actionBtn4').on('click', function() {
            $('#dir').val('').trigger('change');
            $('#dir_action').val('copy');
            $('#itemsModalLabelTwo').text("{{ get_phrases(['copy', 'directory']) }}");
            $('#dir_label').text("{{ get_phrases(['copy', 'to', 'directory']) }}");
            $('.modal_action_two').show();
            $('.modal_action_two_file').hide();
            $('.modal_action_two').text("{{ get_phrases(['copy']) }}");
            $('#items-modal-two').modal('show');
        });

        //move
        $('.actionBtn5').on('click', function() {
            $('#dir').val('').trigger('change');
            $('#dir_action').val('move');
            $('#itemsModalLabelTwo').text("{{ get_phrases(['move', 'directory']) }}");
            $('#dir_label').text("{{ get_phrases(['move', 'to', 'directory']) }}");
            $('.modal_action_two').show();
            $('.modal_action_two_file').hide();
            $('.modal_action_two').text("{{ get_phrases(['move']) }}");
            $('#items-modal-two').modal('show');
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
                        return '<a href="' + data + '" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> </a>';
                    }
                },
                {
                    data: "button",
                    render: function(data, type, row, meta) {
                        return '<a href="javascript:void(0)" data-id="' + data + '" class="fileCopy btn btn-info btn-sm">{{ get_phrases(["copy"]) }}</a> <a href="javascript:void(0)" data-id="' + data + '" class="fileMove btn btn-warning btn-sm">{{ get_phrases(["move"]) }}</a> <a href="javascript:void(0)" data-id="' + data + '" class="delete btn btn-danger btn-sm">{{ get_phrases(["delete"]) }}</a>';
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