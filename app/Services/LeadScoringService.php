<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Support\Carbon;

class LeadScoringService
{
    public function calculate(Lead $lead): int
    {
        $lead->loadMissing(['stage', 'source', 'activities']);

        $score = 0;

        $score += $this->stageScore($lead);
        $score += $this->expectedValueScore($lead);
        $score += $this->sourceScore($lead);
        $score += $this->activityScore($lead);
        $score += $this->followupScore($lead);

        return (int) max(0, min(100, round($score)));
    }

    public function refresh(Lead $lead): int
    {
        $score = $this->calculate($lead);

        $lead->forceFill(['lead_score' => $score])->save();

        return $score;
    }

    protected function stageScore(Lead $lead): int
    {
        $stage = $lead->stage;
        $weights = config('lead_scoring.stage_weights', []);

        if (!$stage) {
            return $weights['open'] ?? 40;
        }

        if ($stage->is_won) {
            return $weights['won'] ?? 100;
        }

        if ($stage->is_lost) {
            return $weights['lost'] ?? 10;
        }

        return $weights['open'] ?? 40;
    }

    protected function expectedValueScore(Lead $lead): int
    {
        $config = config('lead_scoring.expected_value', []);
        $divisor = $config['divisor'] ?? 1000;
        $max = $config['max_weight'] ?? 25;

        if (!$lead->expected_value || $divisor <= 0) {
            return 0;
        }

        return (int) min($max, $lead->expected_value / $divisor);
    }

    protected function sourceScore(Lead $lead): int
    {
        $sourceName = strtolower($lead->source->name ?? '');
        $weights = config('lead_scoring.source_weights', []);

        return $weights[$sourceName] ?? 5;
    }

    protected function activityScore(Lead $lead): int
    {
        $config = config('lead_scoring.activity', []);
        $days = $config['recent_days'] ?? 14;
        $perActivity = $config['weight_per_activity'] ?? 5;
        $max = $config['max_weight'] ?? 20;

        $recentCount = LeadActivity::where('lead_id', $lead->id)
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->count();

        return (int) min($max, $recentCount * $perActivity);
    }

    protected function followupScore(Lead $lead): int
    {
        if (!$lead->next_followup_date) {
            return 0;
        }

        $config = config('lead_scoring.followups', []);
        $windowHours = $config['upcoming_window_hours'] ?? 48;
        $bonus = $config['upcoming_bonus'] ?? 10;
        $penalty = $config['overdue_penalty'] ?? 15;

        $followupDateTime = Carbon::parse($lead->next_followup_date . ' ' . ($lead->next_followup_time ?? '00:00:00'));
        $now = Carbon::now();

        if ($followupDateTime->isPast()) {
            return -$penalty;
        }

        if ($followupDateTime->diffInHours($now) <= $windowHours) {
            return $bonus;
        }

        return 0;
    }
}

