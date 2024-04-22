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

    function calculateAverageOrdersPerDay()
    {
        // Ngày đầu tiên của tháng hiện tại
        $firstDayOfMonth = Carbon::now()->firstOfMonth();

        // Ngày hiện tại
        $currentDate = Carbon::now();

        // Số ngày đã qua trong tháng
        $daysPassed = $currentDate->diffInDays($firstDayOfMonth) + 1;

        $currentOrderInday = Orders::where('order_status', 4)
            ->whereDate('order_date', $currentDate)
            ->count();

        // Truy xuất số lượng đơn hàng của mỗi ngày trong tháng hiện tại từ cơ sở dữ liệu
        $totalOrders = Orders::where('order_status', 4)
            ->whereBetween('order_date', [$firstDayOfMonth, $currentDate])
            ->count();

        // Tính trung bình số đơn hàng mỗi ngày trong tháng hiện tại
        if ($daysPassed > 0) {
            $averageOrdersPerDay = $totalOrders / $daysPassed;
        } else {
            $averageOrdersPerDay = 0;
        }

        if ($averageOrdersPerDay > 0) {
            $growth_rate = (($currentOrderInday / $totalOrders) - 1) * 100;
        } else {
            $growth_rate = 0;
        }

        $growth_rate_formatted = number_format($growth_rate, 1);

        if ($growth_rate_formatted < 0) {
            $growth = false;
        } else {
            $growth = true;
        }


        return response()->json(['currentOrderInday' => $currentOrderInday, 'growth_rate_formatted' => $growth_rate_formatted, 'success' => true, 'growth' => $growth]);
    }

    public function TransactionHistory()
    {
        // Ngày đầu tiên của tháng hiện tại
        $firstDayOfMonth = Carbon::now()->firstOfMonth();

        // Ngày hiện tại
        $currentDate = Carbon::now();

        // Truy xuất tổng doanh thu từ đơn hàng direct trong tháng hiện tại
        $directRevenue = Orders ::where('order_type', 1)
            ->whereBetween('order_date', [$firstDayOfMonth, $currentDate])
            ->sum('total');

        // Truy xuất tổng doanh thu từ đơn hàng online trong tháng hiện tại
        $onlineRevenue = Orders::where('order_type', 0)
            ->whereBetween('order_date', [$firstDayOfMonth, $currentDate])
            ->sum('total');

        return response()->json([
            'direct_revenue' => $directRevenue,
            'online_revenue' => $onlineRevenue,
        ]) ;
    }
}
