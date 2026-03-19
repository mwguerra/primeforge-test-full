@php
    $footerItems = collect([
        ['label' => 'DB', 'value' => config('database.default')],
        ['label' => 'Cache', 'value' => config('cache.default')],
        ['label' => 'Queue', 'value' => config('queue.default')],
        ['label' => 'Session', 'value' => config('session.driver')],
        ['label' => 'Storage', 'value' => config('filesystems.default')],
        ['label' => 'Broadcast', 'value' => config('broadcasting.default')],
    ]);

    $hasHorizon = class_exists(\Laravel\Horizon\Horizon::class);
    $hasReverb = class_exists(\Laravel\Reverb\Reverb::class) || config('broadcasting.default') === 'reverb';
@endphp

<div style="position:fixed;bottom:0;left:0;right:0;z-index:9999;background:rgba(15,15,20,0.95);backdrop-filter:blur(8px);border-top:1px solid rgba(255,255,255,0.1);padding:8px 16px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;font-size:12px;color:#a0a0a0;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
    <span style="color:#6ee7b7;font-weight:600;">{{ config('app.name', 'Laravel') }}</span>
    <span style="color:rgba(255,255,255,0.2);">|</span>
    @foreach($footerItems as $item)
        <span>
            <span style="color:#737373;">{{ $item['label'] }}:</span>
            <span style="color:#d4d4d4;">{{ $item['value'] ?: 'n/a' }}</span>
        </span>
    @endforeach
    @if($hasHorizon)
        <span style="color:rgba(255,255,255,0.2);">|</span>
        <span style="color:#818cf8;">Horizon</span>
    @endif
    @if($hasReverb)
        <span style="color:rgba(255,255,255,0.2);">|</span>
        <span style="color:#f472b6;">Reverb</span>
    @endif
    <span style="color:rgba(255,255,255,0.2);">|</span>
    <span>
        <span style="color:#737373;">PHP</span>
        <span style="color:#d4d4d4;">{{ phpversion() }}</span>
    </span>
    <span style="color:rgba(255,255,255,0.2);">|</span>
    <span>
        <span style="color:#737373;">Node</span>
        <span style="color:#d4d4d4;">{{ trim(exec('node --version')) }}</span>
    </span>
</div>
