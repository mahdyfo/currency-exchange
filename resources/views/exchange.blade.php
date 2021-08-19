@extends('layouts.main')

@section('title') Exchange Page @endsection

@section('contents')
    <exchange-index input-currencies="{{$currencies}}"></exchange-index>
@endsection