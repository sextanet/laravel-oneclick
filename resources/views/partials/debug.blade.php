@if (config('oneclick.debug'))
<pre class="response" data-comment="💡 Request for easy debug">
{{ print_r(request()->all()) }}
</pre>
@endif
