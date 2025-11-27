<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStage;
use Illuminate\Support\Carbon;

class LeadAnalyticsService
{
    public function getOverview(?Carbon $start = null, ?Carbon $end = null): array
    {
        $start = $start ?? Carbon::now()->subMonths(6);
        $end = $end ?? Carbon::now();

        $totalLeads = Lead::count();

        $stageStats = LeadStage::withCount('leads')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'is_won', 'is_lost']);

        $sourceStats = LeadSource::withCount('leads')
            ->orderByDesc('leads_count')
            ->get(['id', 'name']);

        $wonStageIds = LeadStage::where('is_won', true)->pluck('id');
        $lostStageIds = LeadStage::where('is_lost', true)->pluck('id');

        $wonLeads = $wonStageIds->isNotEmpty()
            ? Lead::whereIn('lead_stage_id', $wonStageIds)->count()
            : 0;

        $lostLeads = $lostStageIds->isNotEmpty()
            ? Lead::whereIn('lead_stage_id', $lostStageIds)->count()
            : 0;

        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 2) : 0;

        $leadsOverTime = Lead::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $averageScore = round(Lead::whereNotNull('lead_score')->avg('lead_score') ?? 0, 2);

        return [
            'total_leads' => $totalLeads,
            'won_leads' => $wonLeads,
            'lost_leads' => $lostLeads,
            'conversion_rate' => $conversionRate,
            'average_score' => $averageScore,
            'stage_stats' => $stageStats,
            'source_stats' => $sourceStats,
            'leads_over_time' => $leadsOverTime,
        ];
    }
}

