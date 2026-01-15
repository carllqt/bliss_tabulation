<?php

namespace App\Repositories;

use App\Models\TopFiveScore;

class TopFiveFinalistScoreRepository
{
    public function updateOrCreateScore(int $judgeId, int $topFiveId, string $category, $scoreValue)
    {
        // Use top_five_id instead of candidate_id
        $record = TopFiveScore::firstOrNew([
            'judge_id' => $judgeId,
            'top_five_id' => $topFiveId,
        ]);

        // Update only the current category score
        $record->{$category} = $scoreValue;

        // Recalculate total score
        $record->total_score =
            ($record->accumulative ?? 0) +
            ($record->final_q_and_a ?? 0);

        $record->save();

        return $record;
    }
}
