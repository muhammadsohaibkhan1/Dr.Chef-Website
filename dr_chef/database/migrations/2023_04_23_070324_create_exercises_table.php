<?php

use App\Models\Exercise;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id('exercise_id');
            $table->string('exercise_name');
            $table->string('exercise_image');
            $table->float('met_value');
        });
        $data = [
            [
                'exercise_name' => 'Box Jumps',
                'exercise_image' => 'box_jumps.gif',
                'met_value' => '8',
            ],
            [
                'exercise_name' => 'Crunches',
                'exercise_image' => 'crunches.gif',
                'met_value' => '2.5',
            ],
            [
                'exercise_name' => 'Jogging',
                'exercise_image' => 'jogging.gif',
                'met_value' => '7',
            ],
            [
                'exercise_name' => 'Jumping Jacks',
                'exercise_image' => 'jumping_jacks.gif',
                'met_value' => '8',
            ],
            [
                'exercise_name' => 'Step Ups',
                'exercise_image' => 'step_ups.gif',
                'met_value' => '5',
            ],
            [
                'exercise_name' => 'Walk',
                'exercise_image' => 'walk.gif',
                'met_value' => '3.5',
            ]
        ];
        Exercise::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exercises');
    }
};
