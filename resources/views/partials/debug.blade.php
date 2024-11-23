@if(config('oneclick.debug'))
    <code class="response" data-comment="💡 Response for easy debug">
        {{ var_export(request()->all()) }}
    </code>
@endif
