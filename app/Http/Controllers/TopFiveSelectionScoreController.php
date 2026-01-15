<?php

namespace App\Http\Controllers;

use App\Repositories\TopFiveSelectionScoreRepository;
use Illuminate\Http\Request;

class TopFiveSelectionScoreController extends Controller
{
    protected $scores;

    public function __construct(TopFiveSelectionScoreRepository $scores)
    {
        $this->scores = $scores;
    }

    private function storeScores(Request $request, string $category)
    {
        $request->validate([
            'judge_id' => 'required|exists:users,id',
            'scores' => 'required|array',
        ]);

        $judgeId = $request->input('judge_id');
        $scores = $request->input('scores');

        foreach ($scores as $candidateId => $scoreValue) {
            $this->scores->updateOrCreateScore(
                $judgeId,
                $candidateId,
                $category,
                $scoreValue
            );
        }

        return back();
    }

    public function creative_attire_store(Request $request)
    {
        return $this->storeScores($request, 'creative_attire');
    }

    public function casual_wear_store(Request $request)
    {
        return $this->storeScores($request, 'casual_wear');
    }

    public function swim_wear_store(Request $request)
    {
        return $this->storeScores($request, 'swim_wear');
    }

    public function talent_store(Request $request)
    {
        return $this->storeScores($request, 'talent');
    }

    public function gown_store(Request $request)
    {
        return $this->storeScores($request, 'gown');
    }

    public function q_and_a_store(Request $request)
    {
        return $this->storeScores($request, 'q_and_a');
    }

    public function beauty_store(Request $request)
    {
        return $this->storeScores($request, 'beauty');
    }
}
