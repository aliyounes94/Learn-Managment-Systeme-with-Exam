@extends('layouts.master')
@section('page_title', 'SMS')

@section('content')
    <h2>WELCOME {{ Auth::user()->name }}. This is your DASHBOARD</h2>
    @endsection