<?php

namespace App\Http\Controllers\ResultController;

use App\Http\Controllers\Controller;
use App\Services\TopFiveSelectionService;
use App\Models\TopFiveCandidates;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TopFiveSelectionResultController extends Controller
{
    protected $service;

    public function __construct(TopFiveSelectionService $service)
    {
        $this->service = $service;
    }

    private function renderCategory(string $category, string $name, string $view)
    {
        $results = $this->service->getResultsPerCategory($category);

        return Inertia::render($view, [
            'candidates'   => $results['candidates'],
            'judgeOrder'   => $results['judgeOrder'],
            'categoryName' => $name,
        ]);
    }

    public function creativeAttireResults()
    {
        return $this->renderCategory(
            'creative_attire',
            'Creative Attire',
            'Admin/CreativeAttireResult'
        );
    }

    public function casualWearResults()
    {
        return $this->renderCategory(
            'casual_wear',
            'Casual Wear',
            'Admin/CasualWearResult'
        );
    }

    public function swimWearResults()
    {
        return $this->renderCategory(
            'swim_wear',
            'Swim Wear',
            'Admin/SwimWearResult'
        );
    }

    public function talentResults()
    {
        return $this->renderCategory(
            'talent',
            'Talent',
            'Admin/TalentResult'
        );
    }

    public function gownResults()
    {
        return $this->renderCategory(
            'gown',
            'Gown',
            'Admin/GownResult'
        );
    }

    public function qAndAResults()
    {
        return $this->renderCategory(
            'q_and_a',
            'Q & A',
            'Admin/QandAResult'
        );
    }

    public function beautyResults()
    {
        return $this->renderCategory(
            'beauty',
            'Beauty',
            'Admin/BeautyResult'
        );
    }

    public function topFiveSelectionResults()
    {
        $results = $this->service->getTopFiveSelectionResults();

        return Inertia::render('Admin/TopFiveSelectionResult', [
            'candidates'   => $results['candidates'],
            'categories'   => $results['categories'],
            'categoryName' => 'Top Five Selection',
        ]);
    }

    public function setTopFive(TopFiveSelectionService $service)
    {
        // Clear previous top 5 candidates
        TopFiveCandidates::query()->delete();

        // Get top 5 candidates with accumulative 50%
        $topFive = $service->getTopFiveAccumulative();

        foreach ($topFive as $data) {
            TopFiveCandidates::create([
                'candidate_id' => $data['candidate']->id,
                'accumulative' => $data['accumulative'],
            ]);
        }

        return redirect()->back()->with('success', 'Top 5 candidates saved with accumulative scores successfully!');
    }
}
