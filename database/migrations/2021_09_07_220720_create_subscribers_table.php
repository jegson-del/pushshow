<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->string('business_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('description')->nullable();
            $table->double('wallet_balance')->default(0.00);
            $table->string('logo')->nullable();
            $table->string('facebook_username')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('instagram_username')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('youtube_username')->nullable();
            $table->string('youtube_link')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('password_token')->nullable();
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
        Schema::dropIfExists('subscribers');
    }
}
