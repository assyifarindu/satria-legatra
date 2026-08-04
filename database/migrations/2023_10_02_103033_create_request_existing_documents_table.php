<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestExistingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('request_existings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('base_document_id')->nullable();
            $table->foreign('base_document_id')->references('id')->on('base_documents');
            $table->string('purpose')->nullable();
            $table->integer('status');
            $table->integer('position')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('request_existings');
    }
}
