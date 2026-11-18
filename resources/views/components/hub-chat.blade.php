@php($hubChat = config('mcp-hub.embed'))
@if (! empty($hubChat['script']) && ! empty($hubChat['widget']))
    {{-- MCP Hub embedded chat: users sign in with their AY SHOP account inside the widget. --}}
    <script src="{{ $hubChat['script'] }}" data-widget="{{ $hubChat['widget'] }}" async></script>
@endif
