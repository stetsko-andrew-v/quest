<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DateTime;
/**
 * @property mixed created_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function time_can(){
        $origin =  $this->created_at;
        $now = new DateTime();
        $delta = date_diff($origin, $now);
        $seconds = ($delta->s)
            + ($delta->i * 60)
            + ($delta->h * 60 * 60)
            + ($delta->d * 60 * 60 * 24)
            + ($delta->m * 60 * 60 * 24 * 30)
            + ($delta->y * 60 * 60 * 24 * 365);
        return (1200 - $seconds > 0) ? 1200 - $seconds : 0;
    }
    public function questions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        // select `questions`.*, `order_questions`.`user_id` as `laravel_through_key` from `questions` inner join `order_questions` on `order_questions`.`question_id` = `questions`.`id` where `order_questions`.`user_id` = 3 order by `order_questions`.`order`|'ASC'
        return $this->hasManyThrough(Questions::class, OrderQuestion::class,'user_id','id','id','question_id');
    }
    public function answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Answers::class, "user_id");
    }
    public function bonus_answers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BonusQuestionsAnswers::class, "user_id");
    }
    public function result(){
        //dd($this->answers[1]->question->correct_variants());
        $points = 0;
        foreach ($this->answers as $answer){
            if(is_array(json_decode($answer->answers))){
                $arr_checked=json_decode($answer->answers);
                $arr_correct_checked=[];
                $arr_correct=$answer->question->correct_variants()->toArray();
                foreach ($arr_checked as $answer_checked){
                    foreach ($arr_correct as $correct){
                        if($correct['id']==$answer_checked){
                            $arr_correct_checked[]=$answer_checked;
                            continue;
                        }
                    }
                }
                $m = count($arr_correct_checked) - (count($arr_checked) - count($arr_correct_checked));
                $points += $m/count($arr_correct)>0 ? $m/count($arr_correct) : 0;
            }else{
                if(json_decode($answer->answers)==$answer->question->correct_variants()[0]->id) $points++;
            }
        }
        return $points;
    }
    public function CanBonus(){
        $all_bonus = BonusQuestions::all();
        $can_bonus = null;
        foreach($all_bonus as $one_bonus){
            //dd(json_decode($this->answers()->where('answers.question_id', '=', $one_bonus->need_question->id)->get()[0]->answers));
            if($one_bonus->need_question->type == 1 &&$one_bonus->need_question->correct_variants()[0]->id == json_decode($this->answers()->where('answers.question_id', '=', $one_bonus->need_question->id)->get()[0]->answers))
                $can_bonus[] = $one_bonus;
            if($one_bonus->need_question->type == 2){
                $answer = $this->answers()->where('answers.question_id', '=', $one_bonus->need_question->id)->get()[0];
                $arr_checked=json_decode($answer->answers);
                $arr_correct_checked=[];
                $arr_correct=$answer->question->correct_variants()->toArray();
                foreach ($arr_checked as $answer_checked){
                    foreach ($arr_correct as $correct){
                        if($correct['id']==$answer_checked){
                            $arr_correct_checked[]=$answer_checked;
                            continue;
                        }
                    }
                }
                $m = count($arr_correct_checked) - (count($arr_checked) - count($arr_correct_checked));
                if($m == count($arr_correct)) $can_bonus[] = $one_bonus;
            }
        }
        return $can_bonus;
    }
    public function result_bonus(){
        //dd($this->answers[1]->question->correct_variants());
        $points = 0;
        foreach ($this->bonus_answers as $answer){
            if(is_array(json_decode($answer->answers))){
                $arr_checked=json_decode($answer->answers);
                $arr_correct_checked=[];
                $arr_correct=$answer->question->correct_variants()->toArray();
                foreach ($arr_checked as $answer_checked){
                    foreach ($arr_correct as $correct){
                        if($correct['id']==$answer_checked){
                            $arr_correct_checked[]=$answer_checked;
                            continue;
                        }
                    }
                }
                $m = count($arr_correct_checked) - (count($arr_checked) - count($arr_correct_checked));
                $points += $m/count($arr_correct)>0 ? $m/count($arr_correct) : 0;
            }else{
                if(json_decode($answer->answers)==$answer->question->correct_variants()[0]->id) $points++;
            }
        }
        return $points;
    }
}
