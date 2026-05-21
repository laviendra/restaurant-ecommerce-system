<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get sales summary for a date range.
     * Requirements: 20.1, 20.2
     *
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array
     */
    public function getSalesByDateRange(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $query = Order::where('order_status', 'completed');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $totalOrders = $query->count();
        $totalRevenue = $query->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $averageOrderValue,
            'start_date' => $startDate?->format('Y-m-d'),
            'end_date' => $endDate?->format('Y-m-d'),
        ];
    }

    /**
     * Get top selling products for a date range.
     * Requirements: 20.2
     *
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTopSellingProducts(?Carbon $startDate = null, ?Carbon $endDate = null, int $limit = 10)
    {
        $query = OrderItem::select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('order_status', 'completed');
                
                if ($startDate) {
                    $q->whereDate('created_at', '>=', $startDate);
                }
                
                if ($endDate) {
                    $q->whereDate('created_at', '<=', $endDate);
                }
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_quantity')
            ->limit($limit);

        return $query->get();
    }

    /**
     * Get daily sales trend for a date range.
     * Requirements: 20.3
     *
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return \Illuminate\Support\Collection
     */
    public function getDailySalesTrend(?Carbon $startDate = null, ?Carbon $endDate = null)
    {
        // Default to last 30 days if no dates provided
        if (!$startDate) {
            $startDate = Carbon::now()->subDays(30);
        }
        if (!$endDate) {
            $endDate = Carbon::now();
        }

        $query = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as daily_revenue')
            )
            ->where('order_status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date');

        $salesData = $query->get()->keyBy('date');

        // Fill in missing dates with zero values
        $result = collect();
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayData = $salesData->get($dateStr);
            
            $result->push([
                'date' => $dateStr,
                'order_count' => $dayData ? $dayData->order_count : 0,
                'daily_revenue' => $dayData ? (float) $dayData->daily_revenue : 0,
            ]);
            
            $currentDate->addDay();
        }

        return $result;
    }

    /**
     * Get complete sales report with all metrics.
     * Requirements: 20.1, 20.2, 20.3
     *
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array
     */
    public function getSalesReport(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        return [
            'summary' => $this->getSalesByDateRange($startDate, $endDate),
            'top_products' => $this->getTopSellingProducts($startDate, $endDate),
            'daily_trend' => $this->getDailySalesTrend($startDate, $endDate),
        ];
    }
}
