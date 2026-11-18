{{-- MCP Hub embedded chat, only for signed-in users. The server hands the widget a short-lived,
     read-only API token for the current user, so the chat opens already signed in. --}}
@auth
    @php
        $hubChat = config('mcp-hub.embed');
        $hubToken = null;
        if (! empty($hubChat['script']) && ! empty($hubChat['widget'])) {
            $user = auth()->user();
            $cached = session('hub_chat');
            // One token per session, renewed in the last hour of its life; never shared between users.
            if (! is_array($cached) || ($cached['user'] ?? null) !== $user->id
                || ($cached['exp'] ?? 0) < now()->addHour()->timestamp) {
                $cached = [
                    'user' => $user->id,
                    'token' => $user->createToken('mcp-hub-chat', ['read'], now()->addHours(12))->plainTextToken,
                    'exp' => now()->addHours(12)->timestamp,
                ];
                session(['hub_chat' => $cached]);
            }
            $hubToken = $cached['token'];
        }
    @endphp
    @if ($hubToken)
        <script src="{{ $hubChat['script'] }}" data-widget="{{ $hubChat['widget'] }}" data-token="{{ $hubToken }}" async></script>
    @endif
@endauth
