@extends('skeleton::layout')
@section("title","Localization")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Localization</h2>
            </div>
            <div class="box-body b-t">
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Main Label</label>
                                <input required type="text" class="form-control form-control-sm" name="main_label"/>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Localized</label>
                                <input required type="text" class="form-control form-control-sm" name="localized"/>
                            </div>
                        </div>
                        <div class="col-sm-3" style="margin-top: 2.5%;">
                            <div class="form-group">
                                <label>&nbsp;&nbsp;</label>
                                <button type="submit" class="btn btn-success btn-sm">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        <table class="reportTable display compact cell-border" id="item_list_table">
                            <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Main Head</th>
                                <th>Localized</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($localizations as $key => $localization)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $key }}</td>
                                    <td>{{ $localization }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
