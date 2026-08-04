<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    public function up()
    {
        Schema::create('email_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('base_document_id')->nullable();
            $table->foreign('base_document_id')->references('id')->on('base_documents');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->foreign('document_id')->references('id')->on('documents');
            $table->integer('to')->nullable();
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
        Schema::dropIfExists('email_alerts');
    }
}
