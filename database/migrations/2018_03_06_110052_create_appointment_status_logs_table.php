<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_status_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('appointment_id')->unsigned();
            $table->enum('status', ['pending', 'accepted', 'onway', 'ongoing', 'complete', 'cancelled', 'rejected'])->nullable();
            $table->timestamps();

            $table->foreign('user_id', 'appointment_status_log_foreign_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('appointment_id', 'appointment_status_log_foreign_appointment')
                ->references('id')
                ->on('appointments')
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
        Schema::table('appointment_status_logs', function (Blueprint $table) {
            $table->dropForeign('appointment_status_log_foreign_user');
            $table->dropForeign('appointment_status_log_foreign_appointment');
        });

        Schema::dropIfExists('appointment_status_logs');
    }
}
