<?php

namespace App\Http\Controllers;

use App\Repositories\TopFiveFinalistScoreRepository;
use App\Models\TopFiveCandidates;
use Illuminate\Http\Request;

class TopFiveScoreController extends Controller
{
    protected $scores;

    public function __construct(TopFiveFinalistScoreRepository $scores)
    {
        $this->scores = $scores;
    }

    public function finalQAStore(Request $request)
    {
        $request->validate([
            'judge_id' => 'required|exists:users,id',
            'scores'   => 'required|array',
        ]);

        $judgeId = $request->judge_id;
        $scores  = $request->scores;

        foreach ($scores as $candidateId => $scoreValue) {
            $topFive = TopFiveCandidates::where('candidate_id', $candidateId)->first();

            if (!$topFive) {
                continue;
            }

            $this->scores->updateOrCreateScore(
                $judgeId,
                $topFive->id,
                'final_q_and_a',
                $scoreValue
            );
        }

        return back()->with('success', 'Final Q & A scores saved successfully.');
    }
}
