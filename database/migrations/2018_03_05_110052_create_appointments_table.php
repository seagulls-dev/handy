<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provider_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->date('date');
            $table->time('time_from');
            $table->time('time_to');
            $table->enum('status', ['pending', 'accepted', 'onway', 'ongoing', 'complete', 'cancelled', 'rejected'])->nullable();
            $table->timestamps();

            $table->foreign('user_id', 'appointment_foreign_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('provider_id', 'appointment_foreign_provider_profile')
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
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('appointment_foreign_user');
            $table->dropForeign('appointment_foreign_provider_profile');
        });

        Schema::dropIfExists('appointments');
    }
}
