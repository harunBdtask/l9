@extends('dyes-store::layout')

@section('title', 'Dyes Store')

@section('styles')
@endsection

@section('content')
    <div id="dyes-issue"></div>
    <script src="{{ asset('/js/dyes_and_chemicals/issue.js') }}"></script>
@endsection
