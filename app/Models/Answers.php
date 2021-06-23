<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    use HasFactory;
    public function question(){
        return $this->belongsTo(Questions::class, "question_id","id");
    }
}
