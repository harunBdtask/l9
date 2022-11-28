@extends('skeleton::layout')
@section("title","Replace Remarks")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Replace Remarks</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-4">
                        <form action="{{ url('/po-files-excel/'. $POFileModel->id) }}/replace" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="style">PO No </label>
                                    <input type="text" id="text" readonly value="{{ $POFileModel->po_no }}"
                                           class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="form-group m-t-1">
                                <div class="col-md-12">
                                    <label for="po_no">Remarks <span class="text-danger req">*</span>
                                    </label>
                                    <textarea type="remarks" id="remarks" name="remarks"
                                              class="form-control form-control-sm"></textarea>
                                    @error('remarks')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group" style="margin-left: 3%;">
                                <button style="margin-top: 2%" type="submit" id="submit"
                                        class="btn btn-sm white"><i
                                        class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
