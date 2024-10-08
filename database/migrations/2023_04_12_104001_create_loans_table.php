<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->nullable();
            $table->foreignId('asset_child_id')->nullable();
            $table->foreignId('sbu_id');
            $table->foreignId('user_id');
            $table->string('loan_type', 1);
            $table->string('peminjam');
            $table->longText('description')->nullable();
            $table->date('loan_start_date');
            $table->date('loan_due_date');
            $table->date('loan_date')->nullable();
            $table->boolean('loan_status')->default(0);
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
        Schema::dropIfExists('loans');
    }
}
