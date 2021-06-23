<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusQuestionsVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_questions_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId("question_id")->constrained("bonus_questions")->onDelete("cascade");
            $table->text("variant_name");
            $table->boolean("is_correct");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_questions_variants');
    }
}
