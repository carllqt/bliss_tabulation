<?php

namespace App\Services;

use App\Models\TopFiveScore;
use App\Models\TopFiveCandidates;

class TopFiveService
{
    protected $categories = [
        'accumulative',
        'final_q_and_a',
    ];

    /**
     * Get results per category (NO GENDER)
     */
    public function getResultsPerCategory(string $category)
    {
        $judgeOrder = ['judge_1', 'judge_2', 'judge_3', 'judge_4', 'judge_5'];

        $candidatesList = TopFiveCandidates::with('candidate')
            ->get()
            ->map(fn($item) => [
                'candidate'   => $item->candidate,
                'top_five_id' => $item->id,
            ]);

        $scores = TopFiveScore::with('judge')->get();

        return [
            'candidates' => $this->processCandidates(
                $candidatesList,
                $scores,
                $category,
                $judgeOrder
            ),
            'judgeOrder' => $judgeOrder,
        ];
    }

    protected function processCandidates($candidatesList, $scores, $category, $judgeOrder)
    {
        $processed = [];

        foreach ($candidatesList as $index => $item) {
            $candidate = $item['candidate'];
            $topFiveId = $item['top_five_id'];

            $candidateScores = array_fill_keys($judgeOrder, 0);

            foreach ($scores->where('top_five_id', $topFiveId) as $score) {
                if (in_array($score->judge->name, $judgeOrder)) {
                    $candidateScores[$score->judge->name] =
                        $score->{$category} ?? 0;
                }
            }

            $processed[] = [
                'candidate'        => $candidate,
                'scores'           => $candidateScores,
                'total'            => round(array_sum($candidateScores), 2),
                'rank'             => 0,
                'candidate_number' => $index + 1,
            ];
        }

        return $this->assignRanking($processed);
    }

    public function getTotalResults(): array
    {
        $judgeOrder = ['judge_1', 'judge_2', 'judge_3', 'judge_4', 'judge_5'];

        $candidates = TopFiveCandidates::with('candidate')->get();
        $scores = TopFiveScore::with('judge')->get();

        $processed = [];

        foreach ($candidates as $index => $item) {
            $finalQAScores = array_fill_keys($judgeOrder, 0);

            foreach ($scores->where('top_five_id', $item->id) as $score) {
                if (in_array($score->judge->name, $judgeOrder)) {
                    $finalQAScores[$score->judge->name] =
                        $score->final_q_and_a ?? 0;
                }
            }

            $finalQATotal = array_sum($finalQAScores);

            $processed[] = [
                'candidate' => $item->candidate,
                'scores' => [
                    'accumulative'  => round($item->accumulative, 2),
                    'final_q_and_a' => round($finalQATotal, 2),
                ],
                'total' => round($item->accumulative + $finalQATotal, 2),
                'rank' => 0,
                'candidate_number' => $index + 1,
            ];
        }

        return [
            'candidates' => $this->assignRanking($processed),
            'judgeOrder' => [], // no judge columns here
        ];
    }

    protected function processTotalPerCategory($candidatesList, $scores)
    {
        $processed = [];

        foreach ($candidatesList as $index => $item) {
            $candidate = $item['candidate'];
            $topFiveId = $item['top_five_id'];

            $categoryTotals = array_fill_keys($this->categories, 0);

            foreach ($scores->where('top_five_id', $topFiveId) as $score) {
                foreach ($this->categories as $cat) {
                    $categoryTotals[$cat] += $score->{$cat} ?? 0;
                }
            }

            $processed[] = [
                'candidate'        => $candidate,
                'scores'           => $categoryTotals,
                'total'            => round(array_sum($categoryTotals), 2),
                'rank'             => 0,
                'candidate_number' => $index + 1,
            ];
        }

        return $this->assignRanking($processed);
    }

    private function assignRanking(array $candidates): array
    {
        usort($candidates, fn($a, $b) => $b['total'] <=> $a['total']);

        $rank = 1;
        $lastTotal = null;

        foreach ($candidates as $index => &$c) {
            if ($lastTotal !== null && $c['total'] === $lastTotal) {
                $c['rank'] = $rank;
            } else {
                $rank = $index + 1;
                $c['rank'] = $rank;
                $lastTotal = $c['total'];
            }
        }

        return $candidates;
    }
}
