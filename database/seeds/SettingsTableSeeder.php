<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            ["key" => "currency", "value" => "INR"],
            ["key" => "admin_fee_for_order_in_percent", "value" => "10"],
            ["key" => "tax_in_percent", "value" => "10"],
            ["key" => "support_email", "value" => "admin@example.com"],
            ["key" => "support_phone", "value" => "8181818118"],
            ["key" => "distance_limit", "value" => "30"],
            ["key" => "privacy_policy", "value" => "Demo privacy policy"],
            ["key" => "about_us", "value" => "Demo privacy policy"],
            ["key" => "faq", "value" => "Demo FAQ"],
            ["key" => "terms", "value" => "Demo Terms and Condition"],
            ["key" => "language", "value" => '{
   "en":{
      "appointment_new_title":"New Appointment",
      "appointment_new_body":"You have recieved new appointment for service",
      "appointment_rescheduled_title":"Appointment Rescheduled",
      "appointment_rescheduled_body":"Your appointment with provider is rescheduled",
      "appointment_cancelled_title":"Appointment Cancelled",
      "appointment_cancelled_body":"Client has cancelled the appointment",
      "appointment_rejected_title":"Appointment Rejected",
      "appointment_rejected_body":"Provider has rejected the appointment",
      "appointment_accepted_title":"Appointment Accepted",
      "appointment_accepted_body":"Provider has accepted the appointment",
      "appointment_ongoing_title":"Appointment Started",
      "appointment_ongoing_body":"Provider has started the appointment",
      "appointment_complete_title":"Appointment Complete",
      "appointment_complete_body":"Your appointment with provider is complete"
   }
}'],
        ]);
    }
}
