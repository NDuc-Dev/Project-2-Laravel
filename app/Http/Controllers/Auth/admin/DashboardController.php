<?php

namespace App\Http\Controllers\auth\admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function Index()
    {
        return view('auth.admin.dashboard');
    }

    function calculateRevenueGrowth()
    {
        // Ngày hiện tại
        $currentDate = Carbon::now()->toDateString();

        // Ngày đầu tiên của tháng
        $firstDayOfMonth = Carbon::now()->firstOfMonth()->toDateString();

        // Tính tổng doanh thu của ngày hiện tại
        $currentDayRevenue = Orders::where('order_status', 4)
            ->whereDate('order_date', $currentDate)
            ->sum('total');

        // Tính tổng doanh thu từ ngày đầu tiên của tháng đến ngày hiện tại
        $total_revenue = Orders::where('order_status', 4)
            ->whereBetween('order_date', [$firstDayOfMonth, $currentDate])
            ->sum('total');

        // Tính số lượng ngày từ ngày đầu tiên của tháng đến ngày hiện tại
        $number_of_days = Carbon::now()->diffInDays($firstDayOfMonth) + 1;

        // Tính doanh thu trung bình hàng ngày từ ngày đầu tiên của tháng đến ngày hiện tại
        if ($number_of_days > 0) {
            $average_daily_revenue = $total_revenue / $number_of_days;
        } else {
            $average_daily_revenue = 0;
        }

        // Tính tỷ lệ tăng trưởng của doanh thu so với trung bình ngày trong tháng
        if ($average_daily_revenue > 0) {
            $growth_rate = (($currentDayRevenue / $average_daily_revenue) - 1) * 100;
        } else {
            $growth_rate = 0;
        }

        $growth_rate_formatted = number_format($growth_rate, 1);

        if ($growth_rate_formatted < 0) {
            $growth = false;
        } else {
            $growth = true;
        }

        return response()->json([
            'current_day_revenue' => $currentDayRevenue,
            'growth_rate_formatted' => $growth_rate_formatted,
            'growth' => $growth,
            'success' => true,
        ]);
    }
}
