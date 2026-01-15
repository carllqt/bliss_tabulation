<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\TopFiveSelectionScore;

class TopFiveSelectionService
{
    protected array $categories = [
        'creative_attire',
        'casual_wear',
        'swim_wear',
        'talent',
        'gown',
        'q_and_a',
        'beauty',
    ];

    protected array $judgeOrder = [
        'judge_1',
        'judge_2',
        'judge_3',
        'judge_4',
        'judge_5',
    ];

    public function getResultsPerCategory(string $category): array
    {
        $candidates = Candidate::all();
        $scores = TopFiveSelectionScore::with('judge')->get();

        $processed = [];

        foreach ($candidates as $candidate) {
            $judgeScores = array_fill_keys($this->judgeOrder, 0);

            foreach ($scores->where('candidate_id', $candidate->id) as $score) {
                if (in_array($score->judge->name, $this->judgeOrder)) {
                    $judgeScores[$score->judge->name] = $score->{$category} ?? 0;
                }
            }

            $processed[] = [
                'candidate' => $candidate,
                'scores'    => $judgeScores,
                'total'     => round(array_sum($judgeScores), 2),
                'rank'      => 0,
            ];
        }

        return [
            'candidates' => $this->assignRanking($processed),
            'judgeOrder' => $this->judgeOrder,
        ];
    }

    public function getTopFiveSelectionResults(): array
    {
        $candidates = Candidate::all();
        $scores = TopFiveSelectionScore::with('judge')->get();

        $processed = [];

        foreach ($candidates as $candidate) {
            $categoryTotals = array_fill_keys($this->categories, 0);

            foreach ($scores->where('candidate_id', $candidate->id) as $score) {
                foreach ($this->categories as $cat) {
                    $categoryTotals[$cat] += $score->{$cat} ?? 0;
                }
            }

            $processed[] = [
                'candidate' => $candidate,
                'scores'    => $categoryTotals,
                'total'     => round(array_sum($categoryTotals), 2),
                'rank'      => 0,
            ];
        }

        return [
            'candidates' => $this->assignRanking($processed),
            'categories' => $this->categories,
        ];
    }

    private function assignRanking(array $candidates): array
    {
        usort($candidates, fn($a, $b) => $b['total'] <=> $a['total']);

        $rank = 1;
        $lastTotal = null;

        foreach ($candidates as $index => &$candidate) {
            if ($lastTotal !== null && $candidate['total'] === $lastTotal) {
                $candidate['rank'] = $rank;
            } else {
                $rank = $index + 1;
                $candidate['rank'] = $rank;
                $lastTotal = $candidate['total'];
            }
        }

        return $candidates;
    }

    public function getTopFiveAccumulative(?array $candidateIds = null): array
    {
        // Get all candidates or filter by given IDs
        $candidates = Candidate::when($candidateIds, fn($q) => $q->whereIn('id', $candidateIds))->get();

        // Get all scores
        $scores = TopFiveSelectionScore::with('judge')->get();

        $topFiveAccumulative = [];

        foreach ($candidates as $candidate) {
            $categoryTotals = array_fill_keys($this->categories, 0);

            // Sum all categories for this candidate
            foreach ($scores->where('candidate_id', $candidate->id) as $score) {
                foreach ($this->categories as $cat) {
                    $categoryTotals[$cat] += $score->{$cat} ?? 0;
                }
            }

            $total = array_sum($categoryTotals);
            $accumulative = $total * 0.5; // 50%

            $topFiveAccumulative[] = [
                'candidate'   => $candidate,
                'total'       => $total,
                'accumulative' => round($accumulative, 2),
            ];
        }
        return collect($topFiveAccumulative)
            ->sortByDesc('total')
            ->take(5)
            ->values()
            ->toArray();
    }
}
