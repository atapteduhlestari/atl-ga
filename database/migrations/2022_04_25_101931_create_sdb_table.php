<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSDBTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdb', function (Blueprint $table) {
            $table->id();
            $table->string('sdb_name');
            $table->date('pcs_date')->nullable();
            $table->string('pcs_value')->nullable();
            $table->date('due_date', 2)->nullable();
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
        Schema::dropIfExists('sdb');
    }
}
