<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusQuestionsAnswers extends Model
{
    use HasFactory;
    public function question(){
        return $this->belongsTo(BonusQuestions::class, "question_id","id");
    }
}
