<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::create('base_documents', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number');
            $table->string('description')->nullable();
            $table->integer('pic');
            $table->string('category')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->date('contract_date');
            $table->date('deal_date');
            $table->integer('duration');
            $table->string('note')->nullable();

            $table->integer('status');
            $table->string('file');
            $table->unsignedBigInteger('request_document_id')->nullable();
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
            $table->unsignedBigInteger('alert_id')->nullable();
            $table->foreign('alert_id')->references('id')->on('alerts');
            $table->boolean('is_extend');
            $table->integer('revision')->nullable();
            $table->string('title_ringkasan')->nullable();
            $table->longText('ringkasan')->nullable();
            $table->unsignedBigInteger('title_id')->nullable();
            $table->foreign('title_id')->references('id')->on('titles');
            $table->string('document_type', 50)->nullable();
            $table->string('letter_type', 50)->nullable();
            
            $table->string('letter_purpose', 50)->nullable();
            $table->unsignedBigInteger('haki_type_id')->nullable();
            $table->foreign('haki_type_id')->references('id')->on('haki_types');
            $table->unsignedBigInteger('duty_id')->nullable();
            $table->foreign('duty_id')->references('id')->on('duties');
            $table->string('launch_by')->nullable();
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
        Schema::dropIfExists('base_documents');
    }
}
