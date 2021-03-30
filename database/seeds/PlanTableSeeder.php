<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Rennokki\Plans\Models\PlanFeatureModel;
use Rennokki\Plans\Models\PlanModel;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $premium = PlanModel::create([
            'name' => 'Premium',
            'description' => 'Get 10 leads per day for a month.',
            'price' => 50.0,
            'currency' => env('APP_CURRENCY', 'INR'),
            'duration' => 30, // in days
        ]);

        $economy = PlanModel::create([
            'name' => 'Economy',
            'description' => 'Get 5 leads per day for a month.',
            'price' => 30.0,
            'currency' => env('APP_CURRENCY', 'INR'),
            'duration' => 30, // in days
        ]);

        $basic = PlanModel::create([
            'name' => 'Basic',
            'description' => 'Get 1 leads for a month.',
            'price' => 30.0,
            'currency' => env('APP_CURRENCY', 'INR'),
            'duration' => 30, // in days
        ]);

        // leads per day in each plan
        PlanFeatureModel::create([
            'name' => 'Leads per day',
            'code' => 'leads_per_day',
            'description' => 'leads_per_day',
            'type' => 'limit',
            'limit' => 300,
            'plan_id' => $premium->id
        ]);

        PlanFeatureModel::create([
            'name' => 'Leads per day',
            'code' => 'leads_per_day',
            'description' => 'leads_per_day',
            'type' => 'limit',
            'limit' => 150,
            'plan_id' => $economy->id
        ]);

        PlanFeatureModel::create([
            'name' => 'Leads per day',
            'code' => 'leads_per_day',
            'description' => 'leads_per_day',
            'type' => 'limit',
            'limit' => 30,
            'plan_id' => $basic->id
        ]);

        // advertisement in each plan
        PlanFeatureModel::create([
            'name' => 'Advertise',
            'code' => 'advertise',
            'description' => 'advertise',
            'type' => 'feature',
            'plan_id' => $premium->id
        ]);

        PlanFeatureModel::create([
            'name' => 'Advertise',
            'code' => 'advertise',
            'description' => 'advertise',
            'type' => 'feature',
            'plan_id' => $economy->id
        ]);
    }
}
