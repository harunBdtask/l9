<div class="content-header row align-items-center m-0">
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0">
            <li class="breadcrumb-item"><a href="#">{{ get_phrases(['dashboard']) }}</a></li>
            <li class="breadcrumb-item"><a href="#">{{ get_phrases(['application', 'settings']) }}</a></li>
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header py-2">
                            <!-- l -->
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="languageList" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><i class="fa fa-th-list"></i></th>
                                            <th>{{ get_phrases(['phrase']) }}</th>
                                            <th>{{ get_phrases(['label']) }}</th>
                                            <th>{{ get_phrases(['action']) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i = 1
                                        @endphp
                                        @if (!empty($phrases))
                                            @foreach ($phrases as $key => $value)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                    <td><input type="text" name="update_data" id="phrase-{{ $key }}" class="form-control" value="{{ $value }}"></td>
                                                    <td><button type="button" class="btn btn-success" id="btn-{{ $key }}" onclick="updatePhrase('{{ $key }}')">{{ get_phrases(['update']) }}</button></td>
                                                </tr>
                                                @php
                                                $i++
                                                @endphp
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.body content-->

@push('scripts')
<script>
    function updatePhrase(key){
        var updatedValue = $('#phrase-'+key).val();
        if(updatedValue){
            $('#btn-'+key).text('...');
            $.ajax({
                type : "POST",
                url  : "{{ route('update_phrase') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'updatedValue': updatedValue,
                    'key': key,
                },
                dataType: 'JSON',
                success : function(response) {
                    if(response==1){
                        $('#btn-'+key).html('<i class = "fa fa-check-circle"></i>').addClass('btn-success').removeClass('btn-danger');
                        alert('Saved Successfully !!');
                    }else{
                        alert('Something Went Wrong !!');
                    }
                }
            });
        }else{
            alert('Language phrase is required !!');
            $('#btn-'+key).addClass('btn-danger').removeClass('btn-success');
            $('#phrase-'+key).focus();
        }
    }

    $('document').ready(function() {
        "use strict";

        //reload
        $('.reload').click(function(e) {
            e.preventDefault();
            location.reload();
        });

    });
</script>
@endpush