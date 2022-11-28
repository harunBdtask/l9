@extends('skeleton::layout')
@section('title','Knitting QC')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2> Knitting QC </h2>
            </div>
            <div class="box-body">
                {!! Form::open(['url' => url('/knitting/knitting-qc/search'), 'method' => 'GET', 'id' => 'knitting-qc-program-search-form']) !!}
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label><strong>Roll Barcode</strong></label>
                            {!! Form::text('roll_no', null, ['class' => 'form-control form-control-sm ', 'id' => 'roll_no', 'placeholder' => 'Scan here']) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="reportTable">
                                <tr>
                                    <th style="width: 13%">Company Name</th>
                                    <th style="width: 13%">Booking Type</th>
                                    <th style="width: 13%">Knitting Source</th>
                                    <th style="width: 13%">Program No</th>
                                    <th style="width: 13%">Knit Card No</th>
                                    <th style="width: 13%">Quality Status</th>
                                    <th style="width: 26%" colspan="2">Production Date Range</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::select('factory_id', $factory_options ?? [], factoryId(), ['class' => 'form-control form-control-sm', 'id' => 'factory_id']) !!}
                                    </td>
                                    <td>
                                        <select
                                            name="type"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                            <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                            <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                        </select>
                                    </td>
                                    <td>
                                        {!! Form::select('knitting_source_id', $knitting_sources ?? [], 0, ['class' => 'form-control form-control-sm select2-input', 'id' => 'knitting_source_id']) !!}
                                    </td>
                                    <td>
                                        {!! Form::select('program_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'program_id']) !!}
                                    </td>
                                    <td>
                                        {!! Form::select('knit-card-no', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'knit-card-no', 'placeholder' => 'Select']) !!}
                                    </td>
                                    <td>
                                        {!! Form::select('qc_pending_status', $qc_pending_status_options ?? [], null, ['class' =>
                                        'form-control form-control-sm select2-input', 'id' => 'program_no']) !!}
                                    </td>
                                    <td>
                                        {!! Form::date('from_date', null, ['class' => 'form-control form-control-sm', 'id' => 'from_date'])
                                        !!}
                                    </td>
                                    <td>
                                        {!! Form::date('to_date', null, ['class' => 'form-control form-control-sm', 'id' => 'to_date']) !!}
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-success btn-sm btn-primary"><i
                                                class="fa fa-search"></i> Search</button>
                                    </td>
                                </tr>
                            </table>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h2 class="text-center">
                    Search Result
                </h2>
            </div>
            <div class="box-body">
                <div class="row hide" id="program-search-result-container">
                    <div class="col-sm-12">
                        <h5 id="knitting-source-title"></h5>
                        <div class="table-responsive parentTableFixed" id="knit-qc-program-search-container">
                            <table class="reportTable fixTable">
                                <thead>
                                <tr class="blue-200">
                                    <th>Company Name</th>
                                    <th>Booking Type</th>
                                    <th id="knitting-party-name"></th>
                                    <th>Buyer</th>
                                    <th>Style</th>
                                    <th>Unique ID</th>
                                    <th>PO No</th>
                                    <th>Booking No</th>
                                    <th>Body Part</th>
                                    <th>Color Type</th>
                                    <th>Fabrication</th>
                                    <th>Color</th>
                                    <th>Prog. No</th>
                                    <th>KnitCard. No</th>
                                    <th>Program Qty</th>
                                    <th>Production Qty</th>
                                    <th>Balance Qty</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="knit-qc-knit-card-searched-data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row hide" id="searched-rolls-container">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const factoryDropdown = $('#knitting-qc-program-search-form select[name="factory_id"]');
        const programNoDropdown = $('#knitting-qc-program-search-form select[name="program_id"]');
        const programSearchContainer = $('#program-search-result-container');
        const knitCardSearchResultDom = $('.knit-qc-knit-card-searched-data');
        const knittingSourceTitleDom = $('#knitting-source-title');
        const knittingPartyNameDom = $('#knitting-party-name');
        const searchedRollsContainer = $('#searched-rolls-container');
        $(function () {
            factoryDropdown.select2({
                ajax: {
                    url: '/factories/select2-search',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function (response, params) {
                        return {
                            results: response.data,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select Factory',
                allowClear: true
            });

            programNoDropdown.select2({
                ajax: {
                    url: '/knitting/api/v1/program',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: data,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select Program No',
                allowClear: true
            });

            $(document).on('change', '#program_id', function () {
                const element = $('#knit-card-no');
                element.empty().append(`<option value="">Select</option>`).val('').trigger('change');
                $.ajax({
                    method: 'GET',
                    url: '/knitting/api/v1/get-knit-card-no/' + $(this).val(),
                    success(response) {
                        console.log(response);
                        $.each(response, function (index) {
                            console.log('each', index);
                            element.append(`<option value="${response[index].id}">${response[index].text}</option>`)
                        })
                        element.val('{{ request("knit_card_no") }}').trigger('change');
                    }
                })
            })

            function loadMoreProgramData(page) {
                let paginateTrHtml = document.querySelector('.paginate-tr');
                var form = $('#knitting-qc-program-search-form');
                var data = form.serialize();
                data += `&page=${page}`
                loadNow(5);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: data,
                    beforeSend: function () {
                        paginateTrHtml.remove();
                    }
                }).done(function (response) {
                    if (response.status == 200) {
                        knitCardSearchResultDom.append(response.view);
                    }

                    if (response.status == 500) {
                        alert(response.message);
                    }
                }).fail(function (response) {
                    alert("Something went wrong! Please reload this page!");
                    console.log(response);
                });
            }

            $('#knit-qc-program-search-container').scroll(function () {
                let currentPageDom = document.querySelector('[name="current_page"]');
                let lastPageDom = document.querySelector('[name="last_page"]');
                if (currentPageDom && lastPageDom) {
                    let current_page = Number(currentPageDom.value);
                    let last_page = Number(lastPageDom.value);
                    if (current_page < last_page) {
                        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                            let page = parseInt(current_page) + 1;
                            loadMoreProgramData(page);
                        }
                    }
                }
            });
        });

        $(document).on('submit', '#knitting-qc-program-search-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var data = form.serialize();
            var dataSource;
            searchedRollsContainer.addClass('hide');
            programSearchContainer.addClass('hide');
            knitCardSearchResultDom.empty();
            knittingSourceTitleDom.empty();
            knittingPartyNameDom.empty();
            searchedRollsContainer.empty();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                beforeSend: function () {
                    loadNow(5);
                }
            }).done(function (response) {
                if (response.status == 200) {
                    dataSource = response.data_source;
                    switch (dataSource) {
                        case 'rolls':
                            searchedRollsContainer.removeClass('hide');
                            searchedRollsContainer.append(response.view);
                            break;
                        case 'programs':
                            programSearchContainer.removeClass('hide');
                            knittingSourceTitleDom.text(response.data.knitting_source)
                            knittingPartyNameDom.text(response.data.knitting_party)
                            knitCardSearchResultDom.append(response.view);
                            $('.fixTable').tableHeadFixer();
                            break;
                        default:
                            break;
                    }
                }

                if (response.status == 500) {
                    alert(response.message);
                }
            }).fail(function (response) {
                alert("Something went wrong! Please reload this page!");
                console.log(response);
            });
        });

        $(document).on('click', '.knit-card-qc-action-btn', function (e) {
            e.preventDefault();
            let knitCardId = $(this).attr('data-id');
            searchedRollsContainer.addClass('hide');
            programSearchContainer.addClass('hide');
            knitCardSearchResultDom.empty();
            knittingSourceTitleDom.empty();
            knittingPartyNameDom.empty();
            searchedRollsContainer.empty();
            $.ajax({
                url: '/knitting/knitting-qc/qcable-rolls',
                type: 'get',
                data: {
                    knit_card_id: knitCardId
                },
                beforeSend: function () {
                    loadNow(5);
                }
            }).done(function (response) {
                if (response.status == 200) {
                    searchedRollsContainer.removeClass('hide');
                    searchedRollsContainer.append(response.view);
                }

                if (response.status == 500) {
                    alert(response.message);
                }
            }).fail(function (response) {
                alert("Something went wrong! Please reload this page!");
                console.log(response);
            });
        });

        $(document).on('click', '.goto-qc-section', function (e) {
            e.preventDefault();
            let url = $(this).attr('data-url');
            window.open(url, '_blank')
        });

    </script>
@endsection
