<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('student_name')->index('student_name');
            $table->string('student_address');
            $table->date('student_birthdate');
            $table->date('student_registered_date');
            $table->double('student_paid_price');
            $table->string('student_genre');
            $table->string('student_photo')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('school_id');
            $table->timestamps();
            
            $table->foreign('parent_id', 'students_ibfk_2')->references('id')->on('parents')->onDelete('set NULL')->onUpdate('set NULL');
            $table->foreign('class_id', 'students_ibfk_3')->references('id')->on('classes')->onDelete('set NULL')->onUpdate('set NULL');
            $table->foreign('school_id', 'students_ibfk_4')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
