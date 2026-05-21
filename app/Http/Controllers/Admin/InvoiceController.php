<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display the invoice for an order (admin view).
     */
    public function show(Order $order)
    {
        $invoice = $this->invoiceService->getInvoiceByOrder($order);

        if (!$invoice) {
            $invoice = $this->invoiceService->generateInvoice($order);
        }

        $invoiceData = $this->invoiceService->getInvoiceData($invoice);

        return view('admin.invoices.show', [
            'invoice' => $invoice,
            'invoiceData' => $invoiceData,
            'order' => $order,
        ]);
    }

    /**
     * Display the printer-friendly invoice (admin view).
     */
    public function print(Order $order)
    {
        $invoice = $this->invoiceService->getInvoiceByOrder($order);

        if (!$invoice) {
            $invoice = $this->invoiceService->generateInvoice($order);
        }

        $invoiceData = $this->invoiceService->getInvoiceData($invoice);

        return view('invoices.print', [
            'invoice' => $invoice,
            'invoiceData' => $invoiceData,
        ]);
    }
}
