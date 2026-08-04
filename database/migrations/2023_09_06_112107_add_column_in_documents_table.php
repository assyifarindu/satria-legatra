<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected $connection = 'legatra';
    
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('haki_type_id')->nullable();
            $table->foreign('haki_type_id')->references('id')->on('haki_types');
            $table->unsignedBigInteger('duty_id')->nullable();
            $table->foreign('duty_id')->references('id')->on('duties');
            $table->string('launch_by')->nullable();
        });

        Schema::table('document_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('haki_type_id')->nullable();
            $table->foreign('haki_type_id')->references('id')->on('haki_types');
            $table->unsignedBigInteger('duty_id')->nullable();
            $table->foreign('duty_id')->references('id')->on('duties');
            $table->string('launch_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
        });
    }
}
