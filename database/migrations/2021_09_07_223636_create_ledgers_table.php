<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->double('amount')->default(0.00);
            $table->double('oldBalance')->default(0.00);
            $table->double('newBalance')->default(0.00);
            $table->string('description');
            $table->string('entity');
            $table->integer('entity_id')->comments('this will ref the action id');
            $table->string('type')->comments('this specify transaction type for credit or debit.');
            $table->integer('code')->comments('this specify transaction code');
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
        Schema::dropIfExists('ledgers');
    }
}
