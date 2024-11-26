<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pets', function(Blueprint $table){
            $table->id('pet_id');
            $table->integer('added_user_id');
            $table->integer('animal_id');
            $table->integer('breed_id');
            $table->string('pet_name');
            $table->integer('pet_age');
            $table->string('pet_gender');
            $table->string('pet_height');
            $table->string('pet_weight');
            $table->string('pet_variation');
            $table->text('pet_apearance_desc')->nullable();
            $table->text('pet_nature_desc')->nullable();
            $table->text('pet_image')->nullable;
            $table->string('check_dob')->nullable()->default('0');
            $table->string('check_feed')->nullable()->default('0');
            $table->timestamp('pet_dob')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
