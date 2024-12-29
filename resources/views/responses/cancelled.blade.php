@extends('oneclick::partials.layout')

@section('title', 'Default cancelled response')

@section('content')
<div>This is the <span class="cancelled">cancelled</span> response endpoint</div>

<div>Implement your custom logic by overriding</div>

<pre data-comment="YourController">
LaravelOneclick::setCancelledUrl(route('your.route'));
</pre>

<div>
    Click or touch to see the
    <span class="toggle">$response</span>
</div>

<pre class="hidden" data-comment="Another Dump (you can use any property or method)">
@php(print_r($response))
</pre>

<x-oneclick-debug/>
@endsection
