@extends('oneclick::partials.layout')

@section('title', 'Default response')

@section('content')
<div>This is the <span class="approved">default</span> response endpoint</div>

<div>Implement your custom logic by overriding the method</div>

<pre class="yourModel" data-comment="YourModel.php">
Code...
</pre>

<div>
    Click or touch to see
    <span class="toggle"> $latest_response</span>
</div>

<pre class="hidden" data-comment="Dump (you can use any property or method)">
</pre>

<x-oneclick-debug/>
@endsection
