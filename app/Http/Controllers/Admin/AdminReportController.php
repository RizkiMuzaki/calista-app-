<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\PlaySession;
use App\Models\Mood;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminReportController extends Controller
{
    /**
     * Download laporan progress anak sebagai PDF untuk admin Filament.
     * Endpoint: GET /admin/anak/{childId}/laporan-pdf?period=week|month&date=YYYY-MM-DD
     */
    public function downloadPdf(Request $request, int $childId)
    {
        try {
            $anak = Anak::findOrFail($childId);

            [$start, $end, $period] = $this->resolveSessionPeriod($request);

            $sessions = PlaySession::where('anak_id', $anak->id)
                ->whereBetween('played_on', [$start->toDateString(), $end->toDateString()])
                ->with(['level.module', 'module'])
                ->orderBy('ended_at')
                ->get();

            $payload     = $this->buildSessionReportPayload($anak, $sessions, $start, $end, $period);
            $moduleBreak = $this->buildModuleSessionBreakdown($sessions);
            $daily       = $this->buildDailySessionBreakdown($sessions, $start, $end);

            // VAK dominant style
            $vak = $payload['learning_style'];
            if ($vak['has_data']) {
                $scores = [
                    'Visual'     => $vak['visual'],
                    'Auditori'   => $vak['auditory'],
                    'Kinestetik' => $vak['kinesthetic'],
                ];
                $vak['dominant'] = array_search(max($scores), $scores);
            } else {
                $vak['dominant'] = '-';
            }

            Carbon::setLocale('id');
            $periodLabel = $period === 'week'
                ? 'Minggu ' . $start->isoFormat('D MMM') . ' – ' . $end->isoFormat('D MMM YYYY')
                : 'Bulan ' . $start->isoFormat('MMMM YYYY');

            $viewData = [
                'child' => [
                    'nama'      => $anak->nama_anak,
                    'umur'      => $anak->tanggal_lahir ? $anak->tanggal_lahir->age : null,
                    'join_date' => $anak->created_at->locale('id')->isoFormat('D MMMM YYYY'),
                ],
                'period'        => $payload['period'],
                'periodLabel'   => $periodLabel,
                'printDate'     => now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm'),
                'summary'       => $payload['summary'],
                'learningStyle' => $vak,
                'modules'       => $moduleBreak->toArray(),
                'daily'         => $daily,
                'moods'         => [
                    'summary' => (array) $payload['moods']['summary'],
                    'items'   => collect($payload['moods']['items'])->toArray(),
                ],
                'sessions'      => collect($payload['recent_sessions'])->toArray(),
            ];

            $filename = sprintf(
                'calista-%s-%s-%s.pdf',
                strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $anak->nama_anak)),
                $period,
                $start->format('Ymd')
            );

            $pdf = Pdf::loadView('progress-report', $viewData)
                ->setPaper('a4', 'portrait')
                ->setOption('defaultFont', 'DejaVu Sans')
                ->setOption('isRemoteEnabled', false)
                ->setOption('isHtml5ParserEnabled', true);

            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Admin PDF export error', [
                'child_id' => $childId,
                'error'    => $e->getMessage(),
            ]);
            abort(500, 'Gagal generate laporan PDF: ' . $e->getMessage());
        }
    }

    // ─── Helpers (mirrored from ProgressController) ─────────────────────────

    private function resolveSessionPeriod(Request $request): array
    {
        $period = $request->query('period', 'week');
        if (!in_array($period, ['week', 'month'], true)) {
            $period = 'week';
        }

        $date = $request->query('date')
            ? Carbon::parse($request->query('date'))
            : now();

        if ($period === 'month') {
            return [$date->copy()->startOfMonth(), $date->copy()->endOfMonth(), 'month'];
        }

        return [
            $date->copy()->startOfWeek(Carbon::MONDAY),
            $date->copy()->endOfWeek(Carbon::SUNDAY),
            'week',
        ];
    }

    private function buildModuleSessionBreakdown($sessions)
    {
        return $sessions->groupBy(fn ($s) => optional($s->module)->nama_module ?? optional(optional($s->level)->module)->nama_module ?? 'Lainnya')
            ->map(fn ($group, $key) => [
                'name'       => $key,
                'total'      => $group->count(),
                'completed'  => $group->where('status', 'completed')->count(),
                'avg_score'  => round($group->whereNotNull('score')->avg('score') ?? 0),
                'total_time' => $group->sum('duration_seconds'),
            ])->values();
    }

    private function buildDailySessionBreakdown($sessions, Carbon $start, Carbon $end): array
    {
        $daily = [];
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $dateStr = $cursor->toDateString();
            $daySessions = $sessions->filter(fn ($s) => $s->played_on === $dateStr || (isset($s->played_on) && substr($s->played_on, 0, 10) === $dateStr));
            $daily[] = [
                'date'     => $dateStr,
                'label'    => $cursor->locale('id')->isoFormat('ddd'),
                'count'    => $daySessions->count(),
                'duration' => $daySessions->sum('duration_seconds'),
            ];
            $cursor->addDay();
        }
        return $daily;
    }

    private function buildSessionReportPayload(Anak $anak, $sessions, Carbon $start, Carbon $end, string $period): array
    {
        $completed = $sessions->where('status', 'completed');
        $moods = Mood::where('anak_id', $anak->id)
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->get();

        $moodCounts = $moods->groupBy('mood')->map->count();
        $dominantMood = $moodCounts->sortDesc()->keys()->first() ?? null;

        $visualCount      = $sessions->where('module_type', 'visual')->count();
        $auditoryCount    = $sessions->where('module_type', 'auditory')->count();
        $kinestheticCount = $sessions->where('module_type', 'kinesthetic')->count();
        $totalTyped       = $visualCount + $auditoryCount + $kinestheticCount;

        return [
            'period'  => ['start_date' => $start->toDateString(), 'end_date' => $end->toDateString(), 'type' => $period],
            'child'   => ['nama' => $anak->nama_anak],
            'summary' => [
                'total_sessions'      => $sessions->count(),
                'completed_sessions'  => $completed->count(),
                'avg_score'           => round($completed->whereNotNull('score')->avg('score') ?? 0),
                'total_duration_mins' => round($sessions->sum('duration_seconds') / 60),
                'completion_rate'     => $sessions->count() > 0
                    ? round($completed->count() / $sessions->count() * 100)
                    : 0,
            ],
            'learning_style' => [
                'has_data'    => $totalTyped > 0,
                'visual'      => $totalTyped > 0 ? round($visualCount / $totalTyped * 100) : 0,
                'auditory'    => $totalTyped > 0 ? round($auditoryCount / $totalTyped * 100) : 0,
                'kinesthetic' => $totalTyped > 0 ? round($kinestheticCount / $totalTyped * 100) : 0,
            ],
            'moods' => [
                'summary' => ['dominant' => $dominantMood, 'total' => $moods->count()],
                'items'   => $moods->take(10)->map(fn ($m) => [
                    'mood'       => $m->mood,
                    'created_at' => $m->created_at->locale('id')->isoFormat('D MMM'),
                ])->toArray(),
            ],
            'recent_sessions' => $sessions->sortByDesc('played_on')->take(10)->map(fn ($s) => [
                'module'    => optional($s->module)->nama_module ?? optional(optional($s->level)->module)->nama_module ?? '-',
                'status'    => $s->status,
                'score'     => $s->score,
                'duration'  => round($s->duration_seconds / 60),
                'played_on' => $s->played_on,
            ])->values()->toArray(),
        ];
    }
}
