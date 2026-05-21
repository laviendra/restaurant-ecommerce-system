@extends('layouts.app')

@section('title', 'Invoice ' . $invoiceData['invoice_number'])

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Actions -->
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ url()->previous() }}" class="text-yellow-600 hover:text-yellow-700 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
        <a href="{{ route('invoices.print', $invoice->order_id) }}" target="_blank" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Invoice
        </a>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @include('invoices.partials.invoice-content', ['invoiceData' => $invoiceData])
    </div>
</div>
@endsection
