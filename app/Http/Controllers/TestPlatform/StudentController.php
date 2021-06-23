<?php

namespace App\Http\Controllers\TestPlatform;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\OrderQuestion;
use App\Models\Questions;

class StudentController extends Controller
{
    public function register(Request $request): \Illuminate\Http\RedirectResponse
    {
        $me = new User;
        $me->name = $request->name;
        $me->save();
        $questions = Questions::inRandomOrder()->get();
        Auth::login($me);
        foreach ($questions as $key => $question ){
            $order = new OrderQuestion;
            $order->order = $key;
            $order->user_id = $me->id;
            $order->question_id = $question->id;
            $order->save();
        }
        return redirect()->back();
    }
    public function time(){
        if(Auth::user()){
            return Auth::user()->time_can();
        }else{
            abort(401);
        }
    }
}
