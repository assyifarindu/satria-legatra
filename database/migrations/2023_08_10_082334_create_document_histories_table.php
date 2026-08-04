<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('document_histories', function (Blueprint $table) {
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
            $table->integer('created_by');

            $table->string('company');
            $table->string('serial_number');
            $table->integer('category')->nullable()->change();
            $table->string('priority')->nullable();
            $table->string('pic_nrp')->nullable();
            $table->string('pic_email')->nullable();
            $table->string('pic_name')->nullable();
            $table->integer('updated_by')->nullable();

            $table->dropConstrainedForeignId('company_id');
            $table->dropConstrainedForeignId('request_document_id');

            $table->integer('alert_id')->nullable();

            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')->references('id')->on('documents');

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
        Schema::dropIfExists('document_histories');
    }
}
