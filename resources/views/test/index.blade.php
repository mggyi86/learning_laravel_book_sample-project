@extends('layouts.master')

@section('title')
    <title>Test Page</title>
@endsection

@section('content')
    <h1>Fruit Basket</h1>
    <div>
        <parent v-bind:items="items"></parent>
        <add-item v-on:itemcreated="addItem"></add-item>
    </div>

{{--    <h1>This is My Test Page</h1>
    @if(count($tests)>0)
        @foreach($tests as $test)
            {{ $test }}<br>
        @endforeach
    @else
        <h1> Sorry, nothing to show...</h1>
    @endif

    @if(session()->has('status'))
        {{ session('status') }}
    @endif--}}
@endsection