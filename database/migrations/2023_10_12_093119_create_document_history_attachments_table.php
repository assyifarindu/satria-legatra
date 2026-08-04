<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentHistoryAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('document_history_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_history_id')->nullable();
            $table->foreign('document_history_id')->references('id')->on('document_histories');
            $table->string('file')->nullable();
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
        Schema::dropIfExists('document_history_attachments');
    }
}
