<?php

namespace App\Http\Controllers\TestPlatform;
use App\Http\Controllers\Controller;
use App\Models\Answers;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BonusQuestionsAnswers;
class QuestionController extends Controller
{
    public function index(){
        return view('question');
    }
    public function question()
    {
        if(Auth::user()){
            if(Auth::user()->questions->count()>Auth::user()->answers->count()&&Auth::user()->time_can()>0) {
                $question = Auth::user()->questions()->orderBy("order_questions.order")->get()[Auth::user()->answers->count()];
                $variants = $question->all_variants;
                $vq = ['text' => $question->question_name, 'number' => Auth::user()->answers->count() + 1, 'type' => $question->type];
                return response()->json(['Message'=>'question','question' => $vq, 'variants' => $variants]);
            }elseif(Auth::user()->questions->count()==Auth::user()->answers->count() && count(Auth::user()->CanBonus()) > Auth::user()->bonus_answers->count() && Auth::user()->time_can()>0){
                $question = Auth::user()->CanBonus()[Auth::user()->bonus_answers->count()];
                $variants = $question->all_variants;
                $vq = ['text' => $question->question_name, 'number' => strval(Auth::user()->bonus_answers->count() + 1)."(бонусне)", 'type' => $question->type];
                return response()->json(['Message'=>'question','question' => $vq, 'variants' => $variants]);
            }else{
                return response()->json(['Message'=>'no questions for show']);
            }
        }else{
            return abort(401);
        }
    }
    public function answer(Request $request)
    {
        $request->validate([
            'answer'=>'required'
        ]);
        if(Auth::user()){
            if(Auth::user()->time_can()>0) {
                if(Auth::user()->questions->count()>Auth::user()->answers->count()) {
                    if (Auth::user()->questions()->orderBy("order_questions.order")->get()[Auth::user()->answers->count()]->type == 1 && is_array($request->answer)) abort(400);
                    $answer = new Answers;
                    $answer->user_id = Auth::user()->id;
                    $answer->question_id = Auth::user()->questions()->orderBy("order_questions.order")->get()[Auth::user()->answers->count()]->id;
                    $answer->answers = json_encode($request->answer);
                    $answer->save();
                    return response()->json(["Message" => "posted"]);
                }elseif(Auth::user()->questions->count()==Auth::user()->answers->count() && count(Auth::user()->CanBonus())) {
                    if (Auth::user()->CanBonus()[Auth::user()->bonus_answers->count()]->type == 1 && is_array($request->answer)) abort(400);
                    $answer = new BonusQuestionsAnswers;
                    $answer->user_id = Auth::user()->id;
                    $answer->question_id = Auth::user()->CanBonus()[Auth::user()->bonus_answers->count()]->id;
                    $answer->answers = json_encode($request->answer);
                    $answer->save();
                    return response()->json(["Message" => "posted"]);
                }
            }else if(Auth::user()->time_can()==0){
                return response()->json(["Message" => "time is up"]);
            }
        }else{
            return abort(401);
        }
    }
}
