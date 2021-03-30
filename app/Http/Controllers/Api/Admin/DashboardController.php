<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveLog;
use App\Models\Appointment;
use App\Models\Auth\Role\Role;
use App\Models\Auth\User\User;
use App\Models\Category;
use App\Models\Support;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rennokki\Plans\Models\PlanModel;
use Rinvex\Subscriptions\Models\Plan;
use Illuminate\Support\Facades\DB;
use Validator;


class DashboardController  extends Controller
{
    public function categorySummary(Request $request)
    {
        $categorySummary = DB::table('provider_profiles')->selectRaw('count(*) as total, primary_category_id')
            ->whereNotNull('primary_category_id')
            ->groupBy('primary_category_id')
            ->get();

        foreach ($categorySummary as $summary) {
            $summary->primary_category_id = Category::find($summary->primary_category_id)->title;
        }

        return response()->json([
            "summary" => $categorySummary
        ]);
    }

    public function appointmentAnalytics(Request $request)
    {
        $ridesChartData = Appointment::select(DB::raw('DATE(created_at) as created_at'), DB::raw('count(*) as total'))
            ->whereDate('created_at', '>', Carbon::now()->subDays(7))
            ->whereDate('created_at', '<', Carbon::now())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $summary = [
            ["title" => "Total", "value" => Appointment::whereRaw("1=1")->count()],
            ["title" => "Last Month", "value" => Appointment::whereDate('created_at', '>', Carbon::now()->subDays(30))->count()],
            ["title" => "Last Week", "value" => Appointment::whereDate('created_at', '>', Carbon::now()->subDays(7))->count()],
            ["title" => "Today", "value" => Appointment::whereDate('created_at', '>', Carbon::now())->count()]
        ];

        $chartLabel = array_map([$this, "mapDayName"], $ridesChartData->pluck('created_at')->toArray());

        return response()->json([
            "chart" => [
                "chartLabel" => $chartLabel,
                "linesData" => [$ridesChartData->pluck("total")]
            ],
            "summary" => $summary
        ]);

    }

    public function userAnalytics(Request $request)
    {
        $usersChartData = User::select(DB::raw('DATE(created_at) as created_at'), DB::raw('count(*) as total'))
            ->whereDate('created_at', '>', Carbon::now()->subDays(300))
            ->whereDate('created_at', '<', Carbon::now())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $chartLabel = array_map([$this, "mapDayName"], $usersChartData->pluck('created_at')->toArray());

        $summary = [
            ["title" => "Total", "value" => User::whereRaw("1=1")->count()],
            ["title" => "Last Month", "value" => User::whereDate('created_at', '>', Carbon::now()->subDays(30))->count()],
            ["title" => "Last Week", "value" => User::whereDate('created_at', '>', Carbon::now()->subDays(7))->count()],
            ["title" => "Today", "value" => User::whereDate('created_at', '=', Carbon::now())->count()]
        ];

        return response()->json([
            "chart" => [
                "chartLabel" => $chartLabel,
                "linesData" => [$usersChartData->pluck("total")]
            ],
            "summary" => $summary
        ]);

    }

    public function dailyUserAnalytics(Request $request)
    {
        $dailyActiveUsersData = ActiveLog::select(DB::raw('DATE(created_at) as created_at'), DB::raw('count(*) as total'))
            ->whereDate('created_at', '>', Carbon::now()->subDays(7))
            ->whereDate('created_at', '<', Carbon::now())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        return response()->json([
            "weeks" => array_map([$this, "mapDayName"], $dailyActiveUsersData->pluck('created_at')->toArray()),
            "count" => $dailyActiveUsersData->pluck('total')
        ]);
    }

    private function mapDayName($date) {
        return $date->format("D");
    }

    private function mapCategoryName($date) {
        return $date->format("D");
    }
}
