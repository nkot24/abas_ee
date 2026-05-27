<x-filament-panels::page>

@php
    $topProducts  = array_slice(array_filter($productStats, fn($r) => $r['orders'] > 0), 0, 10);
    $topThree     = array_slice($topProducts, 0, 3);
    $chartLabels  = array_map(fn($r) => mb_strimwidth($r['name'], 0, 26, '…'), $topProducts);
    $chartOrders  = array_column($topProducts, 'orders');
    $chartRevenue = array_column($topProducts, 'revenue');
    $monthPct     = $totalOrders > 0 ? round($monthOrders / $totalOrders * 100) : 0;
    $avgOrder     = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;
    $medals = ['🥇','🥈','🥉'];
    $chartColors = ['#f59e0b','#3b82f6','#10b981','#8b5cf6','#ec4899','#06b6d4','#f97316','#6366f1','#14b8a6','#a855f7'];
@endphp

<style>
  .stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:20px; display:flex; flex-direction:column; gap:12px; box-shadow:0 1px 4px rgba(0,0,0,.05); transition:box-shadow .2s; }
  .stat-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.1); }
  .stat-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
  .chart-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:24px; box-shadow:0 1px 4px rgba(0,0,0,.05); }
  .section-label { font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; color:#9ca3af; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
  .section-label::after { content:''; flex:1; height:1px; background:#f3f4f6; }
  tr.data-row:hover td { background:#f8fafc !important; }
</style>

{{-- ═══ STAT CARDS ═══════════════════════════════════════════ --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:28px">

    @php
    $stats = [
        ['label'=>'Total Orders',    'value'=>$totalOrders,                     'sub'=>null,              'icon_color'=>'#3b82f6','icon_bg'=>'#dbeafe',
         'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label'=>'Total Revenue',   'value'=>'€'.number_format($totalRevenue,2),'sub'=>null,              'icon_color'=>'#10b981','icon_bg'=>'#d1fae5',
         'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'This Month',      'value'=>$monthOrders,                     'sub'=>$monthPct.'% of total','icon_color'=>'#8b5cf6','icon_bg'=>'#ede9fe',
         'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label'=>'Monthly Revenue', 'value'=>'€'.number_format($monthRevenue,2),'sub'=>null,              'icon_color'=>'#6366f1','icon_bg'=>'#e0e7ff',
         'icon'=>'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
        ['label'=>'Avg. Order',      'value'=>'€'.$avgOrder,                    'sub'=>'per paid order',  'icon_color'=>'#ec4899','icon_bg'=>'#fce7f3',
         'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
    ];
    @endphp

    @foreach($stats as $s)
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">{{ $s['label'] }}</span>
            <div class="stat-icon" style="background:{{ $s['icon_bg'] }}">
                <svg style="width:18px;height:18px" fill="none" stroke="{{ $s['icon_color'] }}" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}"/>
                </svg>
            </div>
        </div>
        <div>
            <div style="font-size:26px;font-weight:900;color:#111827;line-height:1.1">{{ $s['value'] }}</div>
            @if($s['sub'])
                <div style="font-size:11px;color:#9ca3af;margin-top:4px">{{ $s['sub'] }}</div>
            @endif
        </div>
        @if($s['label'] === 'This Month' && $totalOrders > 0)
        <div style="height:3px;background:#f3f4f6;border-radius:99px;overflow:hidden">
            <div style="height:100%;width:{{ $monthPct }}%;background:#8b5cf6;border-radius:99px"></div>
        </div>
        @endif
    </div>
    @endforeach

</div>

{{-- ═══ SITE VISITS ════════════════════════════════════════════ --}}
<div class="section-label">Site Traffic</div>
<div style="display:grid;grid-template-columns:1fr 1fr 1fr 3fr;gap:14px;margin-bottom:28px">

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">Today</span>
            <div class="stat-icon" style="background:#fef9c3">
                <svg style="width:18px;height:18px" fill="none" stroke="#ca8a04" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </div>
        </div>
        <div style="font-size:26px;font-weight:900;color:#111827;line-height:1.1">{{ number_format($todayVisits) }}</div>
        <div style="font-size:11px;color:#9ca3af">page loads today</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">This Month</span>
            <div class="stat-icon" style="background:#dcfce7">
                <svg style="width:18px;height:18px" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
        </div>
        <div style="font-size:26px;font-weight:900;color:#111827;line-height:1.1">{{ number_format($monthVisits) }}</div>
        <div style="font-size:11px;color:#9ca3af">visits this month</div>
    </div>

    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">All Time</span>
            <div class="stat-icon" style="background:#e0f2fe">
                <svg style="width:18px;height:18px" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div style="font-size:26px;font-weight:900;color:#111827;line-height:1.1">{{ number_format($totalVisits) }}</div>
        <div style="font-size:11px;color:#9ca3af">total visits</div>
    </div>

    <div class="chart-card" style="padding:18px 24px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
            <div style="font-size:13px;font-weight:800;color:#111827">Last 14 Days</div>
            <span style="font-size:11px;color:#9ca3af">daily page loads</span>
        </div>
        <canvas id="visitsSparkline" style="height:70px;max-height:70px"></canvas>
    </div>

</div>

{{-- ═══ CHARTS ════════════════════════════════════════════════ --}}
@if(count($topProducts) > 0)
<div class="section-label">Analytics</div>
<div style="display:grid;grid-template-columns:3fr 2fr;gap:16px;margin-bottom:28px">

    <div class="chart-card">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px">
            <div>
                <div style="font-size:14px;font-weight:800;color:#111827">Orders by Product</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:2px">Top {{ count($topProducts) }} best-selling products</div>
            </div>
            <span style="background:#fef3c7;color:#d97706;font-size:11px;font-weight:700;padding:4px 10px;border-radius:99px">Top {{ count($topProducts) }}</span>
        </div>
        <canvas id="ordersBarChart" style="max-height:240px"></canvas>
    </div>

    <div class="chart-card">
        <div style="margin-bottom:20px">
            <div style="font-size:14px;font-weight:800;color:#111827">Revenue Split</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:2px">By top products</div>
        </div>
        <canvas id="revenueDoughnut" style="max-height:220px"></canvas>
    </div>

</div>
@endif

{{-- ═══ TOP 3 PODIUM ═══════════════════════════════════════════ --}}
@if(count($topThree) > 0)
<div class="section-label">Top Sellers</div>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:28px">
    @php
    $podium = [
        ['from'=>'#fffbeb','to'=>'#fef3c7','border'=>'#fbbf24','text'=>'#92400e'],
        ['from'=>'#f8fafc','to'=>'#f1f5f9','border'=>'#cbd5e1','text'=>'#475569'],
        ['from'=>'#fff7ed','to'=>'#ffedd5','border'=>'#fed7aa','text'=>'#9a3412'],
    ];
    @endphp
    @foreach($topThree as $k => $row)
    <div style="background:linear-gradient(145deg,{{ $podium[$k]['from'] }},{{ $podium[$k]['to'] }});border:1.5px solid {{ $podium[$k]['border'] }};border-radius:20px;padding:22px;position:relative;overflow:hidden">
        <div style="position:absolute;right:-10px;top:-10px;font-size:60px;opacity:.12;line-height:1">{{ $medals[$k] }}</div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <span style="font-size:32px">{{ $medals[$k] }}</span>
            <span style="background:rgba(255,255,255,.8);border-radius:99px;padding:4px 12px;font-size:12px;font-weight:800;color:{{ $podium[$k]['text'] }};backdrop-filter:blur(4px)">
                {{ $row['orders'] }} {{ $row['orders'] === 1 ? 'order' : 'orders' }}
            </span>
        </div>
        <div style="font-size:13px;font-weight:700;color:#1f2937;margin-bottom:12px;line-height:1.4;min-height:36px">{{ $row['name'] }}</div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px">
            <span style="font-size:11px;color:#6b7280">{{ $row['qty'] }} units sold</span>
            <span style="font-size:14px;font-weight:900;color:{{ $podium[$k]['text'] }}">€{{ number_format($row['revenue'],2) }}</span>
        </div>
        <div style="height:4px;background:rgba(0,0,0,.08);border-radius:99px;overflow:hidden">
            <div style="height:100%;width:{{ round($row['orders']/$maxOrders*100) }}%;background:{{ $podium[$k]['border'] }};border-radius:99px"></div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ═══ PRODUCTS TABLE ═════════════════════════════════════════ --}}
<div class="section-label">All Products</div>
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.05)">

    <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px;border-bottom:1px solid #f3f4f6">
        <div>
            <div style="font-size:15px;font-weight:800;color:#111827">Product Performance</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:2px">Sorted by number of orders</div>
        </div>
        <span style="background:#f3f4f6;border-radius:99px;padding:5px 14px;font-size:12px;font-weight:600;color:#6b7280">
            {{ count($productStats) }} products
        </span>
    </div>

    <div style="overflow-x:auto">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #f3f4f6">
                    <th style="width:48px;padding:11px 16px;text-align:center;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">#</th>
                    <th style="padding:11px 16px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">Product</th>
                    <th style="padding:11px 16px;text-align:right;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">Price</th>
                    <th style="padding:11px 16px;text-align:left;min-width:200px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">Orders</th>
                    <th style="padding:11px 16px;text-align:right;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">Units</th>
                    <th style="padding:11px 16px;text-align:right;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">Revenue</th>
                    <th style="width:48px;padding:11px 16px;text-align:center;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9ca3af">Live</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productStats as $i => $row)
                @php
                    $barPct  = $maxOrders > 0 ? round($row['orders'] / $maxOrders * 100) : 0;
                    $isTop   = $i < 3 && $row['orders'] > 0;
                    $hasData = $row['orders'] > 0;
                    $rowBg   = $isTop ? 'rgba(254,243,199,.4)' : ($i % 2 === 0 ? '#fff' : '#fafafa');
                    $rankColors = ['#f59e0b','#94a3b8','#f97316'];
                @endphp
                <tr class="data-row" style="border-bottom:1px solid #f3f4f6">
                    <td style="padding:11px 16px;text-align:center;background:{{ $rowBg }}">
                        @if($i < 3 && $row['orders'] > 0)
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;border-radius:50%;background:{{ $rankColors[$i] }};color:#fff;font-size:11px;font-weight:900;box-shadow:0 2px 6px rgba(0,0,0,.15)">{{ $i+1 }}</span>
                        @else
                            <span style="font-size:11px;color:#d1d5db;font-weight:600">{{ $i+1 }}</span>
                        @endif
                    </td>

                    <td style="padding:11px 16px;background:{{ $rowBg }}">
                        <div style="display:flex;align-items:center;gap:8px">
                            <span style="font-weight:{{ $hasData ? '600' : '400' }};color:{{ $hasData ? '#1f2937' : '#9ca3af' }}">{{ $row['name'] }}</span>
                            @if(!$row['exists'])
                                <span style="background:#fee2e2;color:#dc2626;font-size:10px;font-weight:700;padding:2px 7px;border-radius:5px">Deleted</span>
                            @endif
                        </div>
                    </td>

                    <td style="padding:11px 16px;text-align:right;font-family:monospace;font-size:12px;color:#6b7280;background:{{ $rowBg }}">
                        {{ $row['price'] !== null ? '€'.number_format($row['price'],2) : '—' }}
                    </td>

                    <td style="padding:11px 16px;background:{{ $rowBg }}">
                        <div style="display:flex;align-items:center;gap:10px">
                            <span style="min-width:20px;text-align:right;font-size:12px;font-weight:900;color:{{ $hasData ? '#d97706' : '#d1d5db' }}">{{ $row['orders'] }}</span>
                            <div style="flex:1;height:7px;background:#f3f4f6;border-radius:99px;overflow:hidden">
                                @if($hasData)
                                <div style="height:100%;width:{{ $barPct }}%;background:linear-gradient(90deg,#f59e0b,#fcd34d);border-radius:99px"></div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td style="padding:11px 16px;text-align:right;font-size:12px;color:{{ $hasData ? '#374151' : '#d1d5db' }};background:{{ $rowBg }}">
                        {{ $row['qty'] > 0 ? $row['qty'] : '—' }}
                    </td>

                    <td style="padding:11px 16px;text-align:right;font-family:monospace;font-size:12px;font-weight:700;color:{{ $row['revenue'] > 0 ? '#111827' : '#d1d5db' }};background:{{ $rowBg }}">
                        {{ $row['revenue'] > 0 ? '€'.number_format($row['revenue'],2) : '—' }}
                    </td>

                    <td style="padding:11px 16px;text-align:center;background:{{ $rowBg }}">
                        @if(!$row['exists'])
                            <span style="color:#e5e7eb;font-size:11px">—</span>
                        @elseif($row['active'])
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.2)"></span>
                        @else
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#e5e7eb"></span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding:56px;text-align:center;color:#9ca3af;font-size:13px">No data yet</td></tr>
                @endforelse
            </tbody>
            @if(count($productStats) > 0)
            <tfoot>
                <tr style="background:#f9fafb;border-top:2px solid #e5e7eb">
                    <td colspan="3" style="padding:13px 16px;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#6b7280">Total</td>
                    <td style="padding:13px 16px">
                        <span style="font-size:13px;font-weight:900;color:#111827">{{ $totalOrders }} orders</span>
                    </td>
                    <td style="padding:13px 16px;text-align:right;font-size:13px;font-weight:900;color:#111827">{{ array_sum(array_column($productStats,'qty')) }}</td>
                    <td style="padding:13px 16px;text-align:right;font-family:monospace;font-size:13px;font-weight:900;color:#111827">€{{ number_format(array_sum(array_column($productStats,'revenue')),2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ═══ CHART.JS ═══════════════════════════════════════════════ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Visits sparkline
    const visitsTrend = @json($visitsTrend);
    const visitLabels = visitsTrend.map(r => {
        const d = new Date(r.date);
        return (d.getMonth()+1) + '/' + d.getDate();
    });
    const visitCounts = visitsTrend.map(r => r.count);

    new Chart(document.getElementById('visitsSparkline'), {
        type: 'bar',
        data: {
            labels: visitLabels,
            datasets: [{
                data: visitCounts,
                backgroundColor: visitCounts.map((v, i) => i === visitCounts.length - 1 ? '#0ea5e9' : '#bae6fd'),
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' ' + ctx.parsed.y + ' visits' } } },
            scales: {
                x: { ticks: { color: '#9ca3af', font: { size: 9 } }, grid: { display: false }, border: { display: false } },
                y: { beginAtZero: true, ticks: { stepSize: 1, color: '#9ca3af', font: { size: 9 } }, grid: { color: '#f3f4f6' }, border: { display: false } }
            }
        }
    });
});
</script>

@if(count($topProducts) > 0)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels   = @json($chartLabels);
    const orders   = @json($chartOrders);
    const revenue  = @json($chartRevenue);
    const colors   = @json($chartColors);

    Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";

    // Bar chart
    new Chart(document.getElementById('ordersBarChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Orders',
                data: orders,
                backgroundColor: colors.map(c => c + 'cc'),
                borderColor: colors,
                borderWidth: 1.5,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.parsed.y + ' orders' } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, color:'#9ca3af', font:{size:11} }, grid: { color:'#f3f4f6' }, border:{display:false} },
                x: { ticks: { color:'#9ca3af', font:{size:10}, maxRotation:30 }, grid: { display:false }, border:{display:false} }
            }
        }
    });

    // Doughnut
    new Chart(document.getElementById('revenueDoughnut'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: revenue,
                backgroundColor: colors.map(c => c + 'dd'),
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font:{size:10}, padding:10, usePointStyle:true, pointStyleWidth:8, color:'#6b7280' }
                },
                tooltip: { callbacks: { label: ctx => ' €' + ctx.parsed.toFixed(2) } }
            }
        }
    });
});
</script>
@endif

</x-filament-panels::page>
