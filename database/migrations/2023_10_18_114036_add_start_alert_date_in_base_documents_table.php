<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartAlertDateInBaseDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::table('base_documents', function (Blueprint $table) {
            $table->date('start_alert_date')->nullable();
            $table->date('end_contract_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('base_documents', function (Blueprint $table) {
            //
        });
    }
}
