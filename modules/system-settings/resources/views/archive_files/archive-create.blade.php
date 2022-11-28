@extends('skeleton::layout')
@section("title","Archive Files")
@section('content')
    <section class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>Archive Files</h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <form id="filesForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2" id="file-upload-section">
                            <div class="m-b-1 col-md-4">
                                <label for="archive_type">Archive Type</label>
                                <select name="archive_type" id="archive_type"
                                        class="form-control form-control-sm select2-input" value="after">
                                    <option value="current">CURRENT ORDER</option>
                                    <option value="previous">PREVIOUS ORDER</option>
                                </select>
                            </div>
                            <div class="m-b-1 col-md-4">
                                <label for="buyer_id">Buyer</label>
                                <select name="buyer_id" id="buyer_id"
                                        class="form-control form-control-sm select2-input">
                                    <option selected hidden disabled>Select Buyer</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-b-1 col-md-4">
                                <label>Style</label>
                                <span id="style-select-section">
                                    <select name="style_id" id="style_select"
                                            class="form-control form-control-sm select2-input">
                                        <option selected hidden disabled>Select Style</option>
                                    </select>
                                </span>
                                <span id="style-input-section" hidden>
                                    <input type="text" class="form-control form-control-sm" placeholder="Write Here"
                                           name="style">
                                </span>
                            </div>
                            <div class="m-b-1 col-md-12">
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th class="file_name_label">File Name
                                            <span class="text-danger">*</span>
                                        </th>
                                        <th class="file_attachment_label">Attachment
                                            <span class="text-danger" style="font-size: 9px"> *Max File Size 2Mb</span>
                                        </th>
                                        <th class="remarks">Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="fileUploadBody">
                                    <tr id="fileUploadRow_1">
                                        <td>1</td>
                                        <td style="width: 15%">
                                            <select class="form-control form-control-sm files-name select2-input"
                                                    name="file_names[]">
                                                <option value="">Select</option>
                                                <option>Order Sheet/PO</option>
                                                <option>Final Costing</option>
                                                <option>Bulk Cutting Marker</option>
                                                <option>Actual Print/Embroidery Design Art Work</option>
                                                <option>Bulk Wash Recipe</option>
                                                <option>Final BOM</option>
                                            </select>
                                        </td>
                                        <td><input class="form-control form-control-sm files-upload" name="files[]"
                                                   type="file"></td>
                                        <td><input class="form-control form-control-sm remarks" name="remarks[]"
                                                   type="text"></td>
                                        <td>
                                            <i class="btn btn-success btn-xs fa fa-plus addRow" data-row="1"></i>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <span class="col-md-12 text-center">
                                <img src="/uploading.gif" alt="uploading" id="uploading" hidden><br>
                                <button class="btn btn-sm btn-success" id="upload-btn"><i class="fa fa-check"></i> Upload
                                </button>
                                <a href="/archive-file" id="refresh-btn" class="btn btn-sm btn-danger">
                                Go Back</a>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <style>
        .form-control-danger ~ .select2 .select2-selection__rendered {
            border: 1px solid red;
        }
    </style>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            let row = 1;
            let maxRow = 6;
            let $tableBody = $("#fileUploadBody");
            let $archiveType = $("#archive_type");
            let $styleSelectSection = $("#style-select-section");
            let $styleInputSection = $("#style-input-section");
            let $styleSelect = $("#style_select");
            let $buyerId = $('#buyer_id');

            $(document).on('submit', "#filesForm", function (e) {
                e.preventDefault();
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: "/archive-file",
                    type: "post",
                    data: formData,
                    processData: false,
                    contentType: false,
                    enctype: 'multipart/form-data',
                    beforeSend() {
                        disableWhileUploading();
                    },
                    success() {
                        setTimeout(() => {
                            enableAfterUploading();
                            window.location.assign("/archive-file");
                        }, 2000);
                    },
                    error(err) {
                        enableAfterUploading();
                        let errors = err.responseJSON.errors;
                        if (Object.keys(errors).includes('buyer_id')) {
                            $("#buyer_id").addClass('form-control-danger');
                        }

                        if (Object.keys(errors).includes('files')) {
                            $(".file_attachment_label").addClass('text-danger');
                        }
                    }
                });
            })

            $(document).on("click", ".addRow", function () {
                tableRowAppend();
            });

            $(document).on("click", ".removeRow", function () {
                let rowNumber = $(this).data("row");
                row = row - 1;
                tableRowRemove(rowNumber);
            });

            $(document).on("change", "#archive_type", function () {
                changeStyleInput();
            });

            $(document).on("change", "#buyer_id", function () {
                fetchStyles();
            })

            function tableRowAppend() {
                if (row >= maxRow) {
                    return false;
                }
                row += 1;

                $tableBody.append(`
                 <tr id="fileUploadRow_${row}">
                    <td>${row}</td>
                    <td>
                        <select class="form-control form-control-sm files-name select2-input-${row}" name="file_names[]">
                            <option value="">Select</option>
                            <option>Order Sheet/PO</option>
                            <option>Final Costing</option>
                            <option>Bulk Cutting Marker</option>
                            <option>Actual Print/Embroidery Design Art Work</option>
                            <option>Bulk Wash Recipe</option>
                            <option>Final BOM</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control form-control-sm" name="files[]" type="file">
                    </td>
                    <td>
                        <input class="form-control form-control-sm" name="remarks[]" type="text">
                    </td>
                    <td>
                        <i class="btn btn-success btn-xs fa fa-plus addRow" data-row="${row}"></i>
                        <i class="btn btn-danger btn-xs fa fa-trash removeRow" data-row="${row}"></i>
                    </td>
                </tr>`);

                $(".select2-input-" + row).select2();
            }

            function tableRowRemove(rowNo) {
                $(`#fileUploadRow_${rowNo}`).remove();
            }

            function disableWhileUploading() {
                $("#uploading").removeAttr("hidden");

                $(".file_attachment_label").removeClass('text-danger');
                $("#buyer_id").removeClass('form-control-danger');
                $(".files-name").attr("disabled", "disabled");
                $(".files-upload").attr("disabled", "disabled");
                $(".addRow").attr("disabled", "disabled");
                $(".upload-btn").attr("disabled", "disabled");
                $(".refresh-btn").attr("disabled", "disabled");
            }

            function enableAfterUploading() {
                $("#uploading").attr("hidden", "hidden");

                for (let i = 2; i <= maxRow; i++) {
                    tableRowRemove(i);
                }

                $(".files-name").removeAttr("disabled").val();
                $(".files-upload").removeAttr("disabled").val();
                $(".addRow").removeAttr("disabled");
                $(".upload-btn").removeAttr("disabled");
                $(".refresh-btn").removeAttr("disabled");
            }

            function fetchStyles() {
                if (!$buyerId.val() || ($archiveType.val() === "previous")) {
                    return false;
                }
                $.ajax({
                    url: "/common-api/buyers-styles/" + $buyerId.val(),
                    type: "get",
                    dataType: "JSON",
                    success(data) {
                        $styleSelect.val('').change();
                        let selectOption = `<option selected hidden disabled>-- SELECT STYLE --</option>`;
                        $.each(data, function (idx, el) {
                            selectOption += `<option value='${el.id}'>${el.text}</option>`;
                        })
                        $styleSelect.html(selectOption);
                    }
                })
            }

            function changeStyleInput() {
                if ($archiveType.val() === "current") {
                    fetchStyles();
                    $styleSelectSection.removeAttr("hidden");
                    $styleInputSection.attr("hidden", "hidden");
                } else {
                    $styleInputSection.removeAttr("hidden");
                    $styleSelectSection.attr("hidden", "hidden");
                }
            }
        });
    </script>
@endsection
