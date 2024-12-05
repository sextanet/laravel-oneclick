@extends('oneclick::partials.layout')

@section('title', 'Default rejected response')

@section('content')
<div>This is the <span class="rejected">rejected</span> response endpoint</div>

<div>Implement your custom logic by overriding</div>

<pre data-comment="YourController">
LaravelOneclick::setRejectedUrl(route('your.route'));
</pre>

<div>
    Click or touch to see
    <span class="toggle"> $latest_response</span>
</div>

<pre class="hidden" data-comment="Dump (you can use any property or method)">
</pre>

<x-oneclick-debug/>
@endsection
