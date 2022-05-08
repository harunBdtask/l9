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
                    <form name="form" id="form" action="#" method="post" enctype="multipart/form-data">
                        <div id="newData">
                            <table width="100%" border="0" cellspacing="0" cellpadding="5">

                                <tr>
                                    <td><?php echo get_phrases(['head', 'code']); ?></td>
                                    <td><input type="text" name="txtHeadCode" id="txtHeadCode" class="form-control" readonly="readonly" /></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrases(['head', 'name']); ?></td>
                                    <td><input type="text" name="txtHeadName" id="txtHeadName" class="form-control" />
                                        <input type="hidden" name="HeadName" id="HeadName" class="form-control" required="required" />
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrases(['parent', 'head']); ?></td>
                                    <td><input type="text" name="txtPHead" id="txtPHead" class="form-control" readonly="readonly" /></td>
                                </tr>
                                <tr>

                                    <td><?php echo get_phrases(['head', 'label']); ?></td>
                                    <td><input type="text" name="txtHeadLevel" id="txtHeadLevel" class="form-control" readonly="readonly" /></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrases(['head', 'type']); ?></td>
                                    <td><input type="text" name="txtHeadType" id="txtHeadType" class="form-control" readonly="readonly" /></td>
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
        console.log(id);
        
    }

    function newdata(id) {
        $.ajax({
            url: _baseURL + 'account/accounts/newForm/' + id,
            type: "GET",
            dataType: "json",
            success: function(data) {
                var headlabel = data.headlabel;
                $('#txtHeadCode').val(data.headcode);
                document.getElementById("txtHeadName").value = '';
                $('#txtPHead').val(data.rowdata.HeadName);
                $('#txtPHeadCode').val(data.rowdata.HeadCode);
                $('#txtHeadLevel').val(headlabel);
                $(".select2").select2();
                $('#btnSave').prop("disabled", false);
                $('#btnSave').show();
                // $('#btnUpdate').hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    /*chart of account subtype*/
    function isSubType_change(stype) {
        if ($('#' + stype).is(":checked")) {
            $.ajax({
                url: _baseURL + "account/accounts/getsubtype/",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data == "") {
                        $('#subtypeContent').html('');
                        $('#subtypeContent').hide();
                    } else {
                        $('#subtypeContent').html(data);
                        $('#subtypeContent').show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        } else {
            $('#subtypeContent').html('');
            $('#subtypeContent').hide();
        }
    }

    $('document').ready(function() {
        "use strict";

        $("#IsTransaction").change(function() {
            var checked = $(this).is(':checked');
            if (checked) {
                $(this).prop("checked", true);
                $("#IsTransaction").val(1);
            } else {
                $(this).prop("checked", false);
                $("#IsTransaction").val('');
            }
        });

        $("#IsGL").change(function() {
            var checked = $(this).is(':checked');
            if (checked) {
                $(this).prop("checked", true);
                $("#IsGL").val(1);
            } else {
                $(this).prop("checked", false);
                $("#IsGL").val('');
            }
        });

    });
</script>
@endpush