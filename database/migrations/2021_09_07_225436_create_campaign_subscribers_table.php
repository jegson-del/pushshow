<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_subscribers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id')->comments('this will refrence campaign');
            $table->unsignedBigInteger('subscriber_id')->comments('this will refrence subscribers');
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
        Schema::dropIfExists('campaign_subscribers');
    }
}
