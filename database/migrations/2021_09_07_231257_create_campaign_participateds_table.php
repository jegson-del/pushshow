<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignParticipatedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_participateds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subscriber_id');
            $table->boolean('facebook')->default(false)->comments('we can use this to mark which task they have followed');
            $table->boolean('instagram')->default(false);
            $table->boolean('youtube')->default(false);
            $table->boolean('status')->comments('pushers mark campaign as complete when they return with link from subscriber page');
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
        Schema::dropIfExists('campaign_participateds');
    }
}
