<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->unsignedBigInteger('badgePositive_id')->nullable();
            $table->unsignedBigInteger('badgeNegative_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->string('facebook_username')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_username')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('youtube_username')->nullable();
            $table->string('youtube_link')->nullable();
            $table->float('wallet_balance')->default(0.00);
            $table->boolean('verified')->default(false);
            $table->boolean('disabled')->default(false);
            $table->float('bonus')->default(0.00);
            $table->string('referrer')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
