<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusQuestions extends Model
{
    use HasFactory;
    public function need_question(){
        return $this->hasOne(Questions::class, 'id', 'visisible_if');
    }
    public function all_variants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonusQuestionsVariants::class, "question_id");
    }
    public function correct_variants(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->all_variants()->where("bonus_questions_variants.is_correct", "=", "1")->get();
    }
}
