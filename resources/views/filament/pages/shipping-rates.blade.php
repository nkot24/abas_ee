<x-filament-panels::page>

<style>
  .sr-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:24px; box-shadow:0 1px 4px rgba(0,0,0,.05); margin-bottom:20px; }
  .sr-section { font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
  .sr-section::after { content:''; flex:1; height:1px; background:#f3f4f6; }
  .sr-input { width:100%; border:1px solid #d1d5db; border-radius:8px; padding:8px 12px; font-size:14px; color:#111827; outline:none; transition:border-color .15s, box-shadow .15s; }
  .sr-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,.12); }
  .sr-label { font-size:12px; font-weight:600; color:#6b7280; margin-bottom:5px; display:block; }
  .sr-badge { display:inline-block; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:700; }
</style>

{{-- ══ TOP BAR ════════════════════════════════════════════════ --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
    <div>
        <p style="font-size:13px;color:#6b7280;margin-top:2px">Edit locker and courier prices. Changes take effect immediately for new orders.</p>
    </div>
    <button wire:click="save" wire:loading.attr="disabled"
        style="background:#6366f1;color:#fff;font-size:13px;font-weight:700;padding:10px 24px;border:none;border-radius:10px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:background .15s"
        onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
        <svg style="width:16px;height:16px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <span wire:loading.remove wire:target="save">Save All Rates</span>
        <span wire:loading wire:target="save">Saving…</span>
    </button>
</div>

{{-- ══ LOCKER / PARCEL TERMINAL ══════════════════════════════ --}}
<div class="sr-section">Locker / Parcel Terminal — Flat Rate</div>
<div class="sr-card">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

        <div>
            <label class="sr-label">
                <span class="sr-badge" style="background:#dbeafe;color:#1d4ed8;margin-right:6px">LV</span>
                Latvia — locker price (€)
            </label>
            <div style="position:relative">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-weight:600;font-size:14px">€</span>
                <input type="number" step="0.01" min="0" wire:model="lockerLv"
                    class="sr-input" style="padding-left:26px">
            </div>
            <p style="font-size:11px;color:#9ca3af;margin-top:5px">Fixed price per parcel, any weight</p>
        </div>

        <div>
            <label class="sr-label">
                <span class="sr-badge" style="background:#dcfce7;color:#15803d;margin-right:4px">LT</span>
                <span class="sr-badge" style="background:#dcfce7;color:#15803d;margin-right:6px">EE</span>
                Baltic — locker price (€)
            </label>
            <div style="position:relative">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-weight:600;font-size:14px">€</span>
                <input type="number" step="0.01" min="0" wire:model="lockerBaltic"
                    class="sr-input" style="padding-left:26px">
            </div>
            <p style="font-size:11px;color:#9ca3af;margin-top:5px">Fixed price per parcel, any weight</p>
        </div>

    </div>
</div>

{{-- ══ COURIER RATES ══════════════════════════════════════════ --}}
<div class="sr-section">Courier — Price by Weight</div>

@php
$courierGroups = [
    ['key' => 'lvRates',     'label' => 'Latvia (LV)',      'badge_bg' => '#dbeafe', 'badge_color' => '#1d4ed8', 'flags' => ['LV']],
    ['key' => 'balticRates', 'label' => 'Baltic (LT + EE)', 'badge_bg' => '#dcfce7', 'badge_color' => '#15803d', 'flags' => ['LT', 'EE']],
];
@endphp

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px">
@foreach($courierGroups as $group)
@php $rates = $this->{$group['key']}; @endphp
<div class="sr-card" style="margin-bottom:0">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px">
        @foreach($group['flags'] as $flag)
        <span class="sr-badge" style="background:{{ $group['badge_bg'] }};color:{{ $group['badge_color'] }}">{{ $flag }}</span>
        @endforeach
        <span style="font-size:13px;font-weight:700;color:#111827">{{ $group['label'] }}</span>
    </div>

    <table style="width:100%;border-collapse:collapse">
        <thead>
            <tr style="border-bottom:2px solid #f3f4f6">
                <th style="padding:6px 8px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af">Up to (kg)</th>
                <th style="padding:6px 8px;text-align:right;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#9ca3af">Price (€)</th>
            </tr>
        </thead>
        <tbody>
        @foreach($rates as $i => $row)
        @php
            $isMax  = $row['weight_up_to'] >= 9000;
            $prevKg = $i > 0 ? $rates[$i-1]['weight_up_to'] : 0;
        @endphp
        <tr style="border-bottom:1px solid #f9fafb">
            <td style="padding:8px 8px">
                @if($isMax)
                    <span style="font-size:12px;color:#9ca3af;font-style:italic">Above {{ $prevKg }} kg</span>
                @else
                    <div style="position:relative">
                        <input type="number" step="0.5" min="0.5"
                            wire:model="{{ $group['key'] }}.{{ $i }}.weight_up_to"
                            class="sr-input" style="padding-right:28px;font-size:12px;padding-top:6px;padding-bottom:6px">
                        <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:11px;color:#9ca3af">kg</span>
                    </div>
                @endif
            </td>
            <td style="padding:8px 8px">
                <div style="position:relative">
                    <span style="position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#9ca3af;font-weight:600;font-size:13px">€</span>
                    <input type="number" step="0.01" min="0"
                        wire:model="{{ $group['key'] }}.{{ $i }}.price"
                        class="sr-input" style="padding-left:22px;text-align:right;font-size:12px;padding-top:6px;padding-bottom:6px">
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endforeach
</div>

<p style="font-size:11px;color:#9ca3af;margin-top:16px;text-align:center">
    Courier rates use actual weight. Weight limits define the upper bound of each bracket.
</p>

</x-filament-panels::page>
