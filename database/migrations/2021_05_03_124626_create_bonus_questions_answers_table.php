<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusQuestionsAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_questions_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->foreignId("question_id")->constrained("bonus_questions")->onDelete("cascade");
            $table->text("answers");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_questions_answers');
    }
}
