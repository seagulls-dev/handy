<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersCategoriesTable extends Migration
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
        Schema::create('providers_categories', function (Blueprint $table) {
            $table->integer('provider_id')->unsigned();
            $table->integer('category_id')->unsigned();

            /*
             * Add Foreign/Unique/Index
             */
            $table->foreign('provider_id', 'provider_categories_foreign_provider_profile')
                ->references('id')
                ->on('provider_profiles')
                ->onDelete('cascade');

            $table->foreign('category_id', 'provider_categories_foreign_category')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->unique(['provider_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers_categories', function (Blueprint $table) {
            $table->dropForeign('provider_categories_foreign_provider_profile');
            $table->dropForeign('provider_categories_foreign_category');
        });

        /*
         * Drop tables
         */
        Schema::dropIfExists('providers_categories');
    }
}
