<?php

namespace App\Http\Controllers\ResultController;

use App\Http\Controllers\Controller;
use App\Services\TopFiveService;
use Inertia\Inertia;

class TopFiveCandidateResultController extends Controller
{
    protected $service;

    public function __construct(TopFiveService $service)
    {
        $this->service = $service;
    }

    /**
     * FINAL Q & A RESULTS (single category, no gender)
     */
    public function finalQAResults()
    {
        $results = $this->service->getResultsPerCategory('final_q_and_a');

        return Inertia::render('Admin/TopFiveCategories/FinalQAResult', [
            'candidates'   => $results['candidates'],
            'judgeOrder'   => $results['judgeOrder'],
            'categoryName' => 'Final Q & A',
        ]);
    }

    /**
     * TOTAL COMBINED RESULTS (no gender)
     */
    public function totalResults()
    {
        $results = $this->service->getTotalResults();

        return Inertia::render('Admin/TopFiveCategories/TotalResults', [
            'candidates'   => $results['candidates'],
            'judgeOrder'   => $results['judgeOrder'],
            'categoryName' => 'Total Combined Scores',
        ]);
    }
}
