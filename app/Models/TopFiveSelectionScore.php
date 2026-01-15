<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopFiveSelectionScore extends Model
{
    /** @use HasFactory<\Database\Factories\TopFiveSelectionScoreFactory> */
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'judge_id',
        'creative_attire',
        'casual_wear',
        'swim_wear',
        'talent',
        'gown',
        'q_and_a',
        'beauty',
        'total_scores',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id');
    }
}
