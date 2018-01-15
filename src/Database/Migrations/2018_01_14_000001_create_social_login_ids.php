<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialLoginIds extends Migration
{
    /**
     * Run the migrations.
     * create the social_login_ids table
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_login_ids', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('provider');
            $table->string('provider_id');
            $table->timestamps();
            $table->index(['provider', 'provider_id']);
        });
    }

    /**
     * Reverse the migrations.
     * drop the social_login_ids table
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_login_ids');
    }
}
