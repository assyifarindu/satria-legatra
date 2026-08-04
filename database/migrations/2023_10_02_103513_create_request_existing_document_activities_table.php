<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestExistingDocumentActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('request_existing_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_existing_id')->nullable();
            $table->foreign('request_existing_id')->references('id')->on('request_existings');
            $table->string('file')->nullable();
            $table->string('description')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('request_existing_activities');
    }
}
