<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Questions extends Model
{
    use HasFactory;
    public function all_variants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Variants::class, "question_id");
    }
    public function correct_variants(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->all_variants()->where("variants.is_correct", "=", "1")->get();
    }
}
