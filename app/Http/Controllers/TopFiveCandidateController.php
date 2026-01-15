<?php

namespace App\Http\Controllers;

use App\Models\TopFiveCandidates;
use App\Models\TopFiveScore;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TopFiveCandidateController extends Controller
{
    /**
     * Render Top Five candidates for Final Q & A
     */
    public function final_q_and_a()
    {
        $judgeId = Auth::id();

        $candidates = TopFiveCandidates::with('candidate')
            ->get()
            ->sortBy(fn($item) => $item->candidate->candidate_number ?? 0)
            ->values()
            ->map(fn($item) => [
                'id' => $item->id,
                'candidate_number' => $item->candidate->candidate_number ?? null,
                'candidate_id' => $item->candidate_id,
                'profile_img' => $item->candidate->profile_img ?? null,
                'first_name' => $item->candidate->first_name ?? null,
                'last_name' => $item->candidate->last_name ?? null,
                'course' => $item->candidate->course ?? null,
                'final_q_and_a' => optional(
                    TopFiveScore::where('top_five_id', $item->id)
                        ->where('judge_id', $judgeId)
                        ->first()
                )->final_q_and_a,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ]);

        return Inertia::render('Categories/TopFiveCategories/FinalQA', [
            'candidates' => $candidates,
            'categoryName' => 'Final Q and A',
            'category' => 'final_q_and_a',
        ]);
    }
}
