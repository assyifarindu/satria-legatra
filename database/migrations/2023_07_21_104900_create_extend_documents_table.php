<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtendDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('extend_documents', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number');
            $table->string('description')->nullable();
            $table->integer('pic');
            $table->string('category')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->date('contract_date');
            $table->date('deal_date');
            $table->integer('duration');
            $table->string('note')->nullable();
            $table->integer('status');
            $table->string('file');
            $table->unsignedBigInteger('request_document_id');
            $table->foreign('request_document_id')->references('id')->on('request_documents');
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')->references('id')->on('documents');
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
        Schema::dropIfExists('extend_documents');
    }
}
