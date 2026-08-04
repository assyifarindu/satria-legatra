<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExtendStepActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::table('extend_document_activity_histories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('extend_document_id');
            $table->unsignedBigInteger('extended_document_id');
            $table->foreign('extended_document_id')->references('id')->on('extended_documents');
        });
        Schema::table('extend_document_step_histories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('extend_document_id');
            $table->unsignedBigInteger('extended_document_id');
            $table->foreign('extended_document_id')->references('id')->on('extended_documents');
        });
        Schema::table('extended_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('request_document_id')->nullable();
            $table->foreign('request_document_id')->references('id')->on('request_documents');
        });
        Schema::dropIfExists('extend_documents');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
