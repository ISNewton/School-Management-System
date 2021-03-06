<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('parent_name')->index('parent_name');
            $table->string('parent_address');
            $table->string('parent_job');
            $table->string('parent_genre');
            $table->string('parent_phone');
            $table->string('parent_photo')->nullable();
            $table->integer('school_id');
            $table->timestamps();
            
            $table->foreign('school_id', 'parents_ibfk_1')->references('id')->on('schools')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parents');
    }
}
