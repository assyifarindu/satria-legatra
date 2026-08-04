<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtendDocumentActivityHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('extend_document_activity_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('extend_document_id');
            $table->foreign('extend_document_id')->references('id')->on('extend_documents');
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
        Schema::dropIfExists('extend_document_activity_histories');
    }
}
