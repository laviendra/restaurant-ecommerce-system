<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display the sales report page with date filter.
     * Requirements: 20.1, 20.2, 20.3
     */
    public function index(Request $request): View
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->subDays(30);
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now();

        $report = $this->reportService->getSalesReport($startDate, $endDate);

        return view('admin.reports.index', [
            'summary' => $report['summary'],
            'topProducts' => $report['top_products'],
            'dailyTrend' => $report['daily_trend'],
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    /**
     * Get sales data for AJAX requests.
     * Requirements: 20.1, 20.2, 20.3
     */
    public function sales(Request $request)
    {
        $startDate = $request->filled('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->subDays(30);
        
        $endDate = $request->filled('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now();

        $report = $this->reportService->getSalesReport($startDate, $endDate);

        return response()->json($report);
    }
}
