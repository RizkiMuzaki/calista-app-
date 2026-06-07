<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  /* =====================================================
     CALISTA — LAPORAN BELAJAR PDF TEMPLATE
     Design: Fun, colorful, easy for parents to read
     ===================================================== */

  @page {
    margin: 20mm 16mm 20mm 16mm;
    size: A4 portrait;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    font-size: 11px;
    color: #1a1a2e;
    background: #fff;
    line-height: 1.5;
  }

  /* ─── COLORS ─── */
  /* Pink   : #E91E8C  */
  /* Gold   : #F5A623  */
  /* Teal   : #0ABFBC  */
  /* Purple : #7B4FBE  */
  /* Green  : #27AE60  */
  /* Bg     : #FFF8FC  */

  /* ─── HEADER ─── */
  .header {
    background: linear-gradient(135deg, #E91E8C 0%, #7B4FBE 100%);
    border-radius: 18px;
    padding: 20px 24px 16px 24px;
    margin-bottom: 18px;
    color: white;
  }

  .header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }

  .brand {
    font-size: 22px;
    font-weight: 900;
    letter-spacing: 2px;
    color: white;
  }

  .brand-sub {
    font-size: 10px;
    color: rgba(255,255,255,0.80);
    letter-spacing: 1px;
    margin-top: 2px;
  }

  .header-date {
    text-align: right;
    font-size: 10px;
    color: rgba(255,255,255,0.85);
  }

  .header-date .date-value {
    font-size: 13px;
    font-weight: 700;
    color: white;
  }

  .child-info {
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,0.25);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .child-name {
    font-size: 20px;
    font-weight: 900;
    color: white;
    letter-spacing: 0.5px;
  }

  .child-meta {
    font-size: 10px;
    color: rgba(255,255,255,0.80);
    margin-top: 2px;
  }

  .period-badge {
    background: rgba(255,255,255,0.22);
    border: 1px solid rgba(255,255,255,0.35);
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 10px;
    color: white;
    text-align: center;
    font-weight: 700;
  }

  /* ─── SECTION TITLE ─── */
  .section-title {
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1.5px;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 8px;
    margin-top: 18px;
    padding-left: 4px;
  }

  /* ─── SUMMARY CARDS ─── */
  .summary-grid {
    display: flex;
    gap: 8px;
    margin-bottom: 4px;
  }

  .summary-card {
    flex: 1;
    border-radius: 14px;
    padding: 12px 10px;
    text-align: center;
  }

  .summary-card .emoji { font-size: 20px; display: block; margin-bottom: 4px; }
  .summary-card .value { font-size: 20px; font-weight: 900; display: block; }
  .summary-card .label { font-size: 9px; font-weight: 700; opacity: 0.75; display: block; margin-top: 1px; text-transform: uppercase; letter-spacing: 0.5px; }

  .card-pink   { background: #fde8f4; color: #E91E8C; }
  .card-gold   { background: #fef3dc; color: #d48900; }
  .card-teal   { background: #ddf7f7; color: #0a9a97; }
  .card-purple { background: #ede5f9; color: #7B4FBE; }
  .card-green  { background: #dff5ea; color: #1e8a4a; }

  /* ─── VAK BARS ─── */
  .vak-row {
    margin-bottom: 7px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .vak-label {
    width: 72px;
    font-size: 10px;
    font-weight: 700;
    color: #444;
    flex-shrink: 0;
  }

  .vak-track {
    flex: 1;
    background: #eee;
    border-radius: 99px;
    height: 12px;
    overflow: hidden;
  }

  .vak-fill {
    height: 100%;
    border-radius: 99px;
  }

  .vak-pct {
    width: 36px;
    text-align: right;
    font-size: 10px;
    font-weight: 900;
    flex-shrink: 0;
  }

  .fill-pink   { background: #E91E8C; }
  .fill-gold   { background: #F5A623; }
  .fill-teal   { background: #0ABFBC; }

  .vak-no-data {
    background: #fef3dc;
    border: 1px dashed #F5A623;
    border-radius: 10px;
    padding: 10px 14px;
    color: #b07a00;
    font-size: 10px;
    font-weight: 700;
  }

  /* ─── DAILY ACTIVITY TABLE ─── */
  .activity-row {
    display: flex;
    gap: 4px;
    align-items: flex-end;
    margin-bottom: 10px;
  }

  .day-col {
    flex: 1;
    text-align: center;
  }

  .day-bar-wrap {
    height: 50px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    margin-bottom: 3px;
  }

  .day-bar {
    width: 18px;
    border-radius: 6px 6px 0 0;
    min-height: 4px;
  }

  .day-label {
    font-size: 8px;
    font-weight: 700;
    color: #888;
  }

  .day-min {
    font-size: 8px;
    font-weight: 900;
    color: #555;
  }

  /* ─── MODULE TABLE ─── */
  .data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
    margin-bottom: 4px;
  }

  .data-table th {
    background: #f3eafd;
    color: #7B4FBE;
    font-weight: 900;
    padding: 7px 8px;
    text-align: left;
    font-size: 9px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }

  .data-table td {
    padding: 7px 8px;
    border-bottom: 1px solid #f0f0f0;
    color: #333;
  }

  .data-table tr:last-child td { border-bottom: none; }
  .data-table tr:nth-child(even) td { background: #fafafa; }

  .data-table .num {
    font-weight: 900;
    text-align: center;
  }

  .badge {
    display: inline-block;
    border-radius: 99px;
    padding: 2px 8px;
    font-size: 9px;
    font-weight: 700;
  }

  .badge-done   { background: #dff5ea; color: #1e8a4a; }
  .badge-partial { background: #fef3dc; color: #d48900; }

  /* ─── MOOD CHIPS ─── */
  .mood-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 8px;
  }

  .mood-chip {
    border-radius: 99px;
    padding: 5px 12px;
    font-size: 10px;
    font-weight: 700;
    border: 1.5px solid rgba(0,0,0,0.08);
  }

  .chip-senang    { background: #fffadc; color: #b8860b; }
  .chip-penasaran { background: #f0eaff; color: #7B4FBE; }
  .chip-takut     { background: #ffe8e8; color: #c0392b; }
  .chip-sedih     { background: #e8f4ff; color: #2980b9; }
  .chip-marah     { background: #ffe8e8; color: #c0392b; }
  .chip-zero      { background: #f5f5f5; color: #bbb; }

  .mood-table td { vertical-align: top; }

  /* ─── SESSION TABLE ─── */
  .sessions-table th { background: #fde8f4; color: #E91E8C; }

  /* ─── FOOTER ─── */
  .footer {
    margin-top: 20px;
    padding-top: 12px;
    border-top: 1.5px dashed #eee;
    text-align: center;
    color: #bbb;
    font-size: 9px;
  }

  .footer strong { color: #E91E8C; }

  /* ─── PAGE BREAK ─── */
  .page-break { page-break-before: always; }

  /* ─── BOX ─── */
  .box {
    background: #FFF8FC;
    border: 1.5px solid #f5d9ee;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 4px;
  }

  .empty-state {
    text-align: center;
    padding: 12px;
    color: #bbb;
    font-size: 10px;
    font-style: italic;
  }

  .highlight { color: #E91E8C; font-weight: 900; }
  .muted { color: #999; font-size: 9px; }
</style>
</head>
<body>

{{-- ══════════════════════════════════════════════════
     HEADER — Brand + Identitas Anak
══════════════════════════════════════════════════ --}}
<div class="header">
  <div class="header-top">
    <div>
      <div class="brand">✨ CALISTA</div>
      <div class="brand-sub">LAPORAN BELAJAR ANAK</div>
    </div>
    <div class="header-date">
      <div>Dicetak</div>
      <div class="date-value">{{ $printDate }}</div>
    </div>
  </div>

  <div class="child-info">
    <div>
      <div class="child-name">🧒 {{ $child['nama'] }}</div>
      <div class="child-meta">
        Umur {{ $child['umur'] ?? '-' }} tahun
        &nbsp;·&nbsp;
        Bergabung sejak {{ $child['join_date'] }}
      </div>
    </div>
    <div class="period-badge">
      📅 {{ $periodLabel }}<br>
      <span style="font-weight:400; font-size:9px;">{{ $period['start_date'] }} — {{ $period['end_date'] }}</span>
    </div>
  </div>
</div>


{{-- ══════════════════════════════════════════════════
     SECTION 1 — RINGKASAN
══════════════════════════════════════════════════ --}}
<div class="section-title">📊 Ringkasan Periode Ini</div>

<div class="summary-grid">
  <div class="summary-card card-pink">
    <span class="emoji">🎮</span>
    <span class="value">{{ $summary['total_sessions'] }}</span>
    <span class="label">Sesi Main</span>
  </div>
  <div class="summary-card card-gold">
    <span class="emoji">⏱️</span>
    <span class="value">{{ number_format($summary['total_play_minutes'], 1) }}</span>
    <span class="label">Menit Belajar</span>
  </div>
  <div class="summary-card card-teal">
    <span class="emoji">🏆</span>
    <span class="value">{{ number_format($summary['avg_score'], 1) }}</span>
    <span class="label">Rata-rata Skor</span>
  </div>
  <div class="summary-card card-purple">
    <span class="emoji">⭐</span>
    <span class="value">{{ $summary['earned_stars'] }}</span>
    <span class="label">Bintang</span>
  </div>
  <div class="summary-card card-green">
    <span class="emoji">✅</span>
    <span class="value">{{ $summary['completion_rate'] }}%</span>
    <span class="label">Selesai</span>
  </div>
</div>


{{-- ══════════════════════════════════════════════════
     SECTION 2 — GAYA BELAJAR VAK
══════════════════════════════════════════════════ --}}
<div class="section-title">🧠 Gaya Belajar (Visual · Auditori · Kinestetik)</div>

<div class="box">
@if($learningStyle['has_data'])
  <div class="vak-row">
    <div class="vak-label">👁️ Visual</div>
    <div class="vak-track">
      <div class="vak-fill fill-pink" style="width: {{ $learningStyle['visual'] }}%;"></div>
    </div>
    <div class="vak-pct" style="color:#E91E8C;">{{ $learningStyle['visual'] }}%</div>
  </div>
  <div class="vak-row">
    <div class="vak-label">👂 Auditori</div>
    <div class="vak-track">
      <div class="vak-fill fill-gold" style="width: {{ $learningStyle['auditory'] }}%;"></div>
    </div>
    <div class="vak-pct" style="color:#d48900;">{{ $learningStyle['auditory'] }}%</div>
  </div>
  <div class="vak-row">
    <div class="vak-label">🤸 Kinestetik</div>
    <div class="vak-track">
      <div class="vak-fill fill-teal" style="width: {{ $learningStyle['kinesthetic'] }}%;"></div>
    </div>
    <div class="vak-pct" style="color:#0a9a97;">{{ $learningStyle['kinesthetic'] }}%</div>
  </div>
  <div style="margin-top:8px; font-size:10px; color:#888;">
    💡 <em>Gaya belajar dominan anak: <strong class="highlight">{{ $learningStyle['dominant'] }}</strong></em>
  </div>
@else
  <div class="vak-no-data">
    🌱 Gaya belajar belum bisa dihitung. Butuh beberapa sesi selesai dengan skor dulu ya!
  </div>
@endif
</div>


{{-- ══════════════════════════════════════════════════
     SECTION 3 — AKTIVITAS HARIAN
══════════════════════════════════════════════════ --}}
<div class="section-title">📅 Aktivitas Harian</div>

<div class="box">
@php
  $maxMin = collect($daily)->max('play_minutes') ?: 1;
  $barColors = ['#E91E8C','#F5A623','#0ABFBC','#7B4FBE','#27AE60','#E91E8C','#F5A623'];
@endphp

<div class="activity-row">
@foreach($daily as $i => $day)
  @php
    $heightPx = max(4, round(($day['play_minutes'] / $maxMin) * 50));
    $color = $day['play_minutes'] > 0 ? ($barColors[$i % 7]) : '#e0e0e0';
    $dayName = \Carbon\Carbon::parse($day['date'])->locale('id')->isoFormat('ddd');
  @endphp
  <div class="day-col">
    <div class="day-bar-wrap">
      <div class="day-bar" style="height:{{ $heightPx }}px; background:{{ $color }};"></div>
    </div>
    <div class="day-label">{{ $dayName }}</div>
    <div class="day-min" style="color:{{ $color }};">
      @if($day['play_minutes'] > 0){{ number_format($day['play_minutes'],1) }}m@else-@endif
    </div>
  </div>
@endforeach
</div>

<table class="data-table" style="margin-top:4px;">
  <tr>
    <th>Hari</th>
    <th style="text-align:center;">Sesi</th>
    <th style="text-align:center;">Selesai</th>
    <th style="text-align:center;">Durasi</th>
  </tr>
  @foreach($daily as $day)
  @if($day['sessions'] > 0)
  <tr>
    <td>{{ \Carbon\Carbon::parse($day['date'])->locale('id')->isoFormat('dddd, D MMM') }}</td>
    <td class="num">{{ $day['sessions'] }}</td>
    <td class="num">{{ $day['completed_sessions'] }}</td>
    <td class="num">{{ number_format($day['play_minutes'],1) }} mnt</td>
  </tr>
  @endif
  @endforeach
  @if(collect($daily)->sum('sessions') == 0)
  <tr><td colspan="4" class="empty-state">Belum ada aktivitas di periode ini 🌙</td></tr>
  @endif
</table>
</div>


{{-- ══════════════════════════════════════════════════
     SECTION 4 — PER MODUL
══════════════════════════════════════════════════ --}}
<div class="section-title">📚 Progress per Modul</div>

@if(count($modules) > 0)
<table class="data-table">
  <tr>
    <th>Modul</th>
    <th style="text-align:center;">Sesi</th>
    <th style="text-align:center;">Selesai</th>
    <th style="text-align:center;">Durasi</th>
    <th style="text-align:center;">Skor Rata-rata</th>
    <th style="text-align:center;">Bintang ⭐</th>
  </tr>
  @foreach($modules as $mod)
  @php
    $moduleEmoji = [
      'membaca'   => '📖',
      'menulis'   => '✏️',
      'berhitung' => '🔢',
      'puzzle'    => '🧩',
    ][$mod['module_slug']] ?? '📚';
  @endphp
  <tr>
    <td><strong>{{ $moduleEmoji }} {{ $mod['module_name'] }}</strong></td>
    <td class="num">{{ $mod['total_sessions'] }}</td>
    <td class="num">{{ $mod['completed_sessions'] }}</td>
    <td class="num">{{ number_format($mod['total_play_minutes'],1) }} mnt</td>
    <td class="num"><span class="highlight">{{ number_format($mod['avg_score'],1) }}</span></td>
    <td class="num" style="color:#F5A623; font-weight:900;">{{ $mod['earned_stars'] }}</td>
  </tr>
  @endforeach
</table>
@else
<div class="box"><div class="empty-state">Belum ada modul yang dimainkan di periode ini 🎮</div></div>
@endif


{{-- ══════════════════════════════════════════════════
     SECTION 5 — MOOD ANAK (halaman baru)
══════════════════════════════════════════════════ --}}
<div class="page-break"></div>

<div class="section-title">😊 Mood Anak di Periode Ini</div>

<div class="box">
@php
  $moodEmoji = [
    'senang'    => '😄',
    'penasaran' => '🤔',
    'takut'     => '😰',
    'sedih'     => '😢',
    'marah'     => '😡',
  ];
  $moodLabel = [
    'senang'    => 'Senang',
    'penasaran' => 'Penasaran',
    'takut'     => 'Takut',
    'sedih'     => 'Sedih',
    'marah'     => 'Marah',
  ];
  $moodSummary = $moods['summary'];
  $totalMoodEntries = array_sum(array_values((array)$moodSummary));
@endphp

@if($totalMoodEntries > 0)
<div class="mood-chips">
  @foreach(['senang','penasaran','takut','sedih','marah'] as $moodKey)
  @php $cnt = (int)($moodSummary[$moodKey] ?? 0); @endphp
  <div class="mood-chip chip-{{ $moodKey }}" style="{{ $cnt == 0 ? 'opacity:0.4' : '' }}">
    {{ $moodEmoji[$moodKey] }} {{ $moodLabel[$moodKey] }}
    <strong>{{ $cnt }}x</strong>
  </div>
  @endforeach
</div>

@if(count($moods['items']) > 0)
<table class="data-table" style="margin-top:8px;">
  <tr>
    <th>Tanggal</th>
    <th>Jam</th>
    <th>Mood</th>
  </tr>
  @foreach(collect($moods['items'])->take(20) as $moodItem)
  <tr>
    <td>{{ \Carbon\Carbon::parse($moodItem['date'])->locale('id')->isoFormat('dddd, D MMM YYYY') }}</td>
    <td class="muted">{{ $moodItem['time'] }}</td>
    <td>
      {{ $moodEmoji[$moodItem['mood_type']] ?? '😐' }}
      <strong>{{ $moodLabel[$moodItem['mood_type']] ?? $moodItem['mood_type'] }}</strong>
    </td>
  </tr>
  @endforeach
</table>
@endif
@else
<div class="empty-state">
  😶 Belum ada data mood di periode ini. Ajak si kecil cek mood setiap hari ya!
</div>
@endif
</div>


{{-- ══════════════════════════════════════════════════
     SECTION 6 — DETAIL SESI
══════════════════════════════════════════════════ --}}
<div class="section-title">🎮 Detail Sesi Bermain</div>

@if(count($sessions) > 0)
<table class="data-table sessions-table">
  <tr>
    <th>Tanggal</th>
    <th>Modul</th>
    <th>Level</th>
    <th style="text-align:center;">Durasi</th>
    <th style="text-align:center;">Skor</th>
    <th style="text-align:center;">⭐</th>
    <th style="text-align:center;">Progress</th>
    <th style="text-align:center;">Status</th>
  </tr>
  @foreach($sessions as $s)
  <tr>
    <td class="muted">{{ \Carbon\Carbon::parse($s['played_on'])->locale('id')->isoFormat('D MMM') }}</td>
    <td>{{ $s['module_name'] ?? '-' }}</td>
    <td style="font-size:9px;">{{ $s['level_title'] ?? '-' }}</td>
    <td class="num muted">{{ round($s['duration_seconds'] / 60, 1) }}m</td>
    <td class="num"><span class="highlight">{{ $s['score'] }}</span></td>
    <td class="num" style="color:#F5A623;">{{ $s['bintang'] }}</td>
    <td class="num muted">{{ $s['current_item'] }}/{{ $s['total_items'] }}</td>
    <td class="num">
      @if($s['status'] === 'completed')
        <span class="badge badge-done">✅ Selesai</span>
      @else
        <span class="badge badge-partial">⏳ Lanjut</span>
      @endif
    </td>
  </tr>
  @endforeach
</table>
@else
<div class="box"><div class="empty-state">Belum ada sesi bermain di periode ini 🌙</div></div>
@endif


{{-- ══════════════════════════════════════════════════
     FOOTER
══════════════════════════════════════════════════ --}}
<div class="footer">
  Dibuat otomatis oleh <strong>Calista</strong> — Teman Belajar Si Kecil 🌟
  &nbsp;·&nbsp;
  {{ $printDate }}
  &nbsp;·&nbsp;
  Data periode {{ $period['start_date'] }} s/d {{ $period['end_date'] }}
</div>

</body>
</html>
