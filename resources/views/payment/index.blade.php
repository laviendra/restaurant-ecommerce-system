@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('checkout.index') }}" class="text-red-600 hover:text-red-700 flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Checkout
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Bank Transfer Payment</h1>
        </div>

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Bank Details & Upload Form -->
            <div class="space-y-6">
                <!-- Bank Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Bank Account Details</h2>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <p class="text-sm text-yellow-800">
                            <strong>Important:</strong> Please transfer the exact amount and upload your payment proof below.
                        </p>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Bank Name</span>
                            <span class="font-semibold text-gray-900">{{ $bankDetails['bank_name'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Account Number</span>
                            <span class="font-semibold text-gray-900 font-mono">{{ $bankDetails['account_number'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Account Name</span>
                            <span class="font-semibold text-gray-900">{{ $bankDetails['account_name'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600">Amount to Transfer</span>
                            <span class="font-bold text-red-600 text-lg">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload Payment Proof</h2>
                    
                    <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Proof Image <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition" id="dropzone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none">
                                            <span>Upload a file</span>
                                            <input id="payment_proof" name="payment_proof" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 5MB</p>
                                </div>
                            </div>
                            <div id="preview-container" class="mt-4 hidden">
                                <img id="preview-image" class="max-h-48 mx-auto rounded-lg shadow" alt="Preview">
                                <p id="file-name" class="text-sm text-gray-600 text-center mt-2"></p>
                            </div>
                            @error('payment_proof')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button 
                            type="submit" 
                            class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        >
                            Submit Payment Proof
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 h-fit">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Delivery Address</p>
                    <p class="text-gray-900">{{ $checkoutData['delivery_address'] }}</p>
                </div>
                
                @if($checkoutData['notes'])
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Order Notes</p>
                    <p class="text-gray-900">{{ $checkoutData['notes'] }}</p>
                </div>
                @endif
                
                <div class="border-t border-gray-200 pt-4">
                    <h3 class="font-medium text-gray-900 mb-3">Items</h3>
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                        <div class="flex justify-between items-start text-sm">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                @if($item->notes)
                                <p class="text-gray-500 italic text-xs">{{ $item->notes }}</p>
                                @endif
                            </div>
                            <span class="font-medium text-gray-900">Rp {{ number_format($item->getSubtotal(), 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total</span>
                        <span class="text-red-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const fileInput = document.getElementById('payment_proof');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const fileName = document.getElementById('file-name');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                fileName.textContent = file.name;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
