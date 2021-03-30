<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderPortfoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * User and roles relation table
         */
        Schema::create('provider_portfolios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provider_id')->unsigned();
            $table->string('image_url', 500);
            $table->string('link', 500)->nullable();
            $table->timestamps();

            /*
             * Add Foreign/Unique/Index
             */
            $table->foreign('provider_id', 'provider_portfolios_foreign_provider_profile')
                ->references('id')
                ->on('provider_profiles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_portfolios', function (Blueprint $table) {
            $table->dropForeign('provider_portfolios_foreign_provider_profile');
        });

        /*
         * Drop tables
         */
        Schema::dropIfExists('provider_portfolios');
    }
}
