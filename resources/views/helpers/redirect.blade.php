@extends('oneclick::partials.layout')

@section('title', config('oneclick.texts.creating.title'))

@section('content')
    {{ config('oneclick.texts.creating.content') }}

    <form action="{{ $url }}" method="POST" style="display: none;">
        <input type="hidden" name="TBK_TOKEN" value="{{ $token }}">

        <button>Redirect</button>
    </form>

    <script>
        document.querySelector('form').submit();
    </script>
@endsection
