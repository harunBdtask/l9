@extends('dyes-store::layout')
@section('title', 'Stock In | Dyes & Chemicals')

@section('styles')
@endsection

@section('content')
    <div id="dyes-receive"></div>
    <script src="{{ asset('/js/dyes_and_chemicals/receive.js') }}"></script>
@endsection
