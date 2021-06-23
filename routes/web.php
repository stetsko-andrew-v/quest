<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'App\\Http\\Controllers\\TestPlatform\\QuestionController@index')->name('index');
Route::post("/register",'App\\Http\\Controllers\\TestPlatform\\StudentController@register')->name('reg_test');
Route::get("/time_can",'App\\Http\\Controllers\\TestPlatform\\StudentController@time')->name('time_can');
Route::get('/about',function(){
    return view("about");
})->name('about');
Route::get("/question","App\\Http\\Controllers\\TestPlatform\\QuestionController@question")->name('show_question');
Route::post('/post_answer', "App\\Http\\Controllers\\TestPlatform\\QuestionController@answer")->name('post_answer');
// if you want to see results, you must uncomment lines 25-31
Route::get('/results35r432852369546', function (){
    foreach (User::all() as $user){
        $result = $user->result();
        $result_bonus = $user->result_bonus();
        echo $user->id." ".$user->name." ".$result." ".$result_bonus." ".($result+$result_bonus)."<br>";
    }
});
