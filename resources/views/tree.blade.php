<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Forms</a></li>
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
                        <a href="#" class="action-item"><i class="ti-reload"></i></a>
                        <div class="dropdown action-item" data-toggle="dropdown">
                            <a href="#" class="action-item"><i class="ti-more-alt"></i></a>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item">Refresh</a>
                                <a href="#" class="dropdown-item">Manage Widgets</a>
                                <a href="#" class="dropdown-item">Settings</a>
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
                                    <td><?php echo get_phrases(['directory', 'name']); ?></td>
                                    <td><input type="text" name="txtHeadName" id="txtHeadName" class="form-control" /></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrases(['parent', 'directory']); ?></td>
                                    <td><input type="text" name="txtPHead" id="txtPHead" class="form-control" readonly="readonly" /></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-primary actionBtn">Create</button>
                                        <button class="btn btn-success actionBtn2">Rename</button>
                                        <button class="btn btn-danger actionBtn3">Delete</button>
                                    </td>

                                </tr>

                            </table>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->

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




    });
</script>
@endpush