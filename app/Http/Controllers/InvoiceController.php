<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display the invoice for an order.
     */
    public function show(Order $order)
    {
        // Ensure the user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

        $invoice = $this->invoiceService->getInvoiceByOrder($order);

        if (!$invoice) {
            // Generate invoice if it doesn't exist
            $invoice = $this->invoiceService->generateInvoice($order);
        }

        $invoiceData = $this->invoiceService->getInvoiceData($invoice);

        return view('invoices.show', [
            'invoice' => $invoice,
            'invoiceData' => $invoiceData,
        ]);
    }

    /**
     * Display the printer-friendly invoice.
     */
    public function print(Order $order)
    {
        // Ensure the user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to invoice.');
        }

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
