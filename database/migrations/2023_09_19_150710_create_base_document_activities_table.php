<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseDocumentActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('base_document_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('base_document_id');
            $table->foreign('base_document_id')->references('id')->on('base_documents');
            $table->integer('user_id');
            $table->string('activity_name');
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
        Schema::dropIfExists('base_document_activities');
    }
}
