<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('primary_category_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->boolean('is_verified')->default(false);
            $table->string('document_url', 500)->nullable();
	        $table->string('image_url', 500)->nullable();
            $table->double('price')->nullable();
            $table->enum('price_type', ['visit', 'hour'])->nullable();
            $table->string('address')->nullable();
            $table->decimal('longitude', 15, 7)->default(0.0);
            $table->decimal('latitude', 15, 7)->default(0.0);
            $table->text('about')->nullable();
            $table->timestamps();

            $table->foreign('user_id', 'provider_profiles_foreign_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('primary_category_id', 'provider_profiles_foreign_categories')
                ->references('id')
                ->on('categories')
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
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->dropForeign('provider_profiles_foreign_user');
            $table->dropForeign('provider_profiles_foreign_categories');
        });

        Schema::dropIfExists('provider_profiles');
    }
}
