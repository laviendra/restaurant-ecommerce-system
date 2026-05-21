<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Generate a unique invoice number.
     */
    public function generateInvoiceNumber(): string
    {
        do {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }

    /**
     * Generate an invoice for an order.
     */
    public function generateInvoice(Order $order): Invoice
    {
        // Check if invoice already exists for this order
        if ($order->invoice) {
            return $order->invoice;
        }

        $invoiceNumber = $this->generateInvoiceNumber();
        $invoiceData = $this->compileInvoiceData($order, $invoiceNumber);

        return Invoice::create([
            'invoice_number' => $invoiceNumber,
            'order_id' => $order->id,
            'invoice_data' => $invoiceData,
        ]);
    }

    /**
     * Compile invoice data from order details.
     */
    public function compileInvoiceData(Order $order, string $invoiceNumber): array
    {
        $order->load(['items', 'user']);

        return [
            'order_number' => $order->order_number,
            'invoice_number' => $invoiceNumber,
            'date' => $order->created_at->format('Y-m-d H:i:s'),
            'customer' => [
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
                'address' => $order->user->address,
            ],
            'delivery_address' => $order->delivery_address,
            'items' => $order->items->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'price' => (float) $item->product_price,
                    'quantity' => $item->quantity,
                    'subtotal' => (float) $item->subtotal,
                    'notes' => $item->notes,
                ];
            })->toArray(),
            'total_amount' => (float) $order->total_amount,
            'payment_method' => $order->payment_method,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'notes' => $order->notes,
        ];
    }

    /**
     * Get invoice data for display/printing.
     */
    public function getInvoiceData(Invoice $invoice): array
    {
        return $invoice->invoice_data;
    }

    /**
     * Get invoice by order.
     */
    public function getInvoiceByOrder(Order $order): ?Invoice
    {
        return $order->invoice;
    }

    /**
     * Get invoice by invoice number.
     */
    public function getInvoiceByNumber(string $invoiceNumber): ?Invoice
    {
        return Invoice::where('invoice_number', $invoiceNumber)->first();
    }
}
