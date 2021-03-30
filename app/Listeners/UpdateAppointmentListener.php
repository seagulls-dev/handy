<?php

namespace App\Listeners\Auth;

use App\Helpers\Language;
use OneSignal;
use App\Events\UpdateAppointment;
use App\Helpers\PushNotificationHelper;
use Illuminate\Support\Facades\Log;


class UpdateAppointmentListener
{
    private $event;
    private $appointment;
    private $rescheduled;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewAppointment $event
     * @return void
     */
    public function handle(UpdateAppointment $event)
    {
        try {
            $this->event = $event;
            $this->appointment = $event->appointment;
            $this->rescheduled = $event->rescheduled;

            $clientLanguageCode = $this->appointment->user->language;
            $providerLanguageCode = $this->appointment->provider->user->language;
            $clientLanguage = new Language($clientLanguageCode);
            $providerLanguage = new Language($providerLanguageCode);

            if($this->rescheduled) {
                // send notification to user
                if($this->appointment->user->fcm_registration_id) {
                    $oneSignal = PushNotificationHelper::getOneSignalInstance();
                    $oneSignal->sendNotificationToUser($clientLanguage->get('appointment_rescheduled_title'),
                        $this->appointment->user->fcm_registration_id,
                        null,
                        ["title" => $clientLanguage->get('appointment_rescheduled_title'), "body" => $clientLanguage->get('appointment_rescheduled_body'), "appoinment_id" => $this->appointment->id]);
                }
            } else if($this->appointment->status == "cancelled") {
                if($this->appointment->provider->user->fcm_registration_id) {
                    // send notification to provider
                    $oneSignal = PushNotificationHelper::getOneSignalInstance("provider");
                    $oneSignal->sendNotificationToUser($providerLanguage->get('appointment_cancelled_title'),
                        $this->appointment->provider->user->fcm_registration_id,
                        null,
                        ["title" => $providerLanguage->get('appointment_cancelled_title'), "body" => $providerLanguage->get('appointment_cancelled_body'), "appoinment_id" => $this->appointment->id]);
                }
            } else if($this->appointment->status == "rejected") {
                // send notification to user
                if($this->appointment->user->fcm_registration_id) {
                    $oneSignal = PushNotificationHelper::getOneSignalInstance();
                    $oneSignal->sendNotificationToUser($clientLanguage->get('appointment_rejected_title'),
                        $this->appointment->user->fcm_registration_id,
                        null,
                        ["title" => $clientLanguage->get('appointment_rejected_title'), "body" => $clientLanguage->get('appointment_rejected_body'), "appoinment_id" => $this->appointment->id]);
                }
            } else if($this->appointment->status == "accepted") {
                // send notification to user
                if($this->appointment->user->fcm_registration_id) {
                    $oneSignal = PushNotificationHelper::getOneSignalInstance();
                    $oneSignal->sendNotificationToUser($clientLanguage->get('appointment_accepted_title'),
                        $this->appointment->user->fcm_registration_id,
                        null,
                        ["title" => $clientLanguage->get('appointment_accepted_title'), "body" => $clientLanguage->get('appointment_accepted_body'), "appoinment_id" => $this->appointment->id]);
                }
            } else if($this->appointment->status == "ongoing") {
                // send notification to user
                if($this->appointment->user->fcm_registration_id) {
                    $oneSignal = PushNotificationHelper::getOneSignalInstance();
                    $oneSignal->sendNotificationToUser($clientLanguage->get('appointment_ongoing_title'),
                        $this->appointment->user->fcm_registration_id,
                        null,
                        ["title" => $clientLanguage->get('appointment_ongoing_title'), "body" => $clientLanguage->get('appointment_ongoing_body'), "appoinment_id" => $this->appointment->id]);
                }
            } else if($this->appointment->status == "complete") {
                // send notification to user
                if($this->appointment->user->fcm_registration_id) {
                    $oneSignal = PushNotificationHelper::getOneSignalInstance();
                    $oneSignal->sendNotificationToUser($clientLanguage->get('appointment_complete_title'),
                        $this->appointment->user->fcm_registration_id,
                        null,
                        ["title" => $clientLanguage->get('appointment_complete_title'), "body" => $clientLanguage->get('appointment_complete_body'), "appoinment_id" => $this->appointment->id]);
                }
            }

        } catch (\Exception $ex) {
            Log::error('Exception: Notification not sent', [$ex->getMessage(), $ex->getTraceAsString()]);
        }
    }
}
