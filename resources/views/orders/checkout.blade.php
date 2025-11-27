@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Cart', 'url' => route('cart.index')],
            ['label' => 'Checkout']
        ]"
        eyebrow="Complete your order"
        title="Checkout"
        description="Review your order details and complete your purchase."
    />

<section class="py-10 md:py-16">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8">
                <h2 class="heading3 text-black mb-6">Checkout</h2>
                
                <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <!-- Shipping Address -->
                    <div class="bg-white rounded-2xl mb-6 overflow-hidden">
                        <div class="p-6 border-b border-line">
                            <h5 class="heading6 text-black">Shipping Address</h5>
                        </div>
                        <div class="p-6">
                            @if($savedAddresses->count() > 0)
                            <div class="mb-6">
                                <label class="caption2 text-secondary block mb-3">Use Saved Address</label>
                                <div class="space-y-3">
                                    @foreach($savedAddresses as $address)
                                    <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors saved-address-option">
                                        <input type="radio" name="saved_shipping_address_id" value="{{ $address->id }}" 
                                               class="mt-1 w-5 h-5 saved-address-radio" 
                                               data-street="{{ $address->street }}"
                                               data-city="{{ $address->city }}"
                                               data-state="{{ $address->state }}"
                                               data-postal-code="{{ $address->postal_code }}"
                                               {{ old('saved_shipping_address_id', $defaultAddress?->id) == $address->id ? 'checked' : '' }}>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-title font-semibold">{{ $address->label }}</span>
                                                @if($address->is_default)
                                                    <span class="caption2 bg-green text-white px-2 py-1 rounded">Default</span>
                                                @endif
                                            </div>
                                            <div class="caption2 text-secondary">
                                                {{ $address->street }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                            </div>
                                        </div>
                                    </label>
                                    @endforeach
                                    <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors">
                                        <input type="radio" name="saved_shipping_address_id" value="" class="mt-1 w-5 h-5 saved-address-radio" id="use_new_shipping_address">
                                        <div class="flex-1">
                                            <span class="text-title font-semibold">Use New Address</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div id="shipping_address_fields" class="{{ $savedAddresses->count() > 0 && $defaultAddress ? 'hidden' : '' }}">
                                <div class="mb-4">
                                    <label for="shipping_street" class="caption2 text-secondary block mb-2">Street Address *</label>
                                    <input type="text" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line @error('shipping_address.street') border-red @enderror" 
                                           id="shipping_street" name="shipping_address[street]" value="{{ old('shipping_address.street', $defaultAddress->street ?? '') }}">
                                    @error('shipping_address.street')
                                        <div class="caption2 text-red mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <label for="shipping_state" class="caption2 text-secondary block mb-2">State/Province *</label>
                                        <input type="text" 
                                               class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line location-search-input" 
                                               id="shipping_state" 
                                               name="shipping_address[state]" 
                                               list="shipping_state_list"
                                               placeholder="Search or select state"
                                               autocomplete="off"
                                               value="{{ old('shipping_address.state', $defaultAddress->state ?? '') }}">
                                        <datalist id="shipping_state_list"></datalist>
                                        @error('shipping_address.state')
                                            <div class="caption2 text-red mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="shipping_city" class="caption2 text-secondary block mb-2">City *</label>
                                        <input type="text" 
                                               class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line location-search-input" 
                                               id="shipping_city" 
                                               name="shipping_address[city]" 
                                               list="shipping_city_list"
                                               placeholder="Search or select city"
                                               autocomplete="off"
                                               value="{{ old('shipping_address.city', $defaultAddress->city ?? '') }}">
                                        <datalist id="shipping_city_list"></datalist>
                                        @error('shipping_address.city')
                                            <div class="caption2 text-red mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="shipping_postal_code" class="caption2 text-secondary block mb-2">Postal Code *</label>
                                        <input type="text" 
                                               class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" 
                                               id="shipping_postal_code" 
                                               name="shipping_address[postal_code]" 
                                               placeholder="Postal code"
                                               value="{{ old('shipping_address.postal_code', $defaultAddress->postal_code ?? '') }}">
                                        @error('shipping_address.postal_code')
                                            <div class="caption2 text-red mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="shipping_address[country]" value="Saudi Arabia">
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="bg-white rounded-2xl mb-6 overflow-hidden">
                        <div class="p-6 border-b border-line">
                            <h5 class="heading6 text-black">Billing Address</h5>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="same_as_shipping" name="same_as_shipping" value="1" class="w-5 h-5" checked>
                                    <span class="caption1">Same as shipping address</span>
                                </label>
                            </div>

                            <div id="billing_address_fields" class="hidden">
                                @if($savedAddresses->count() > 0)
                                <div class="mb-6">
                                    <label class="caption2 text-secondary block mb-3">Use Saved Address</label>
                                    <div class="space-y-3">
                                        @foreach($savedAddresses as $address)
                                        <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors">
                                            <input type="radio" name="saved_billing_address_id" value="{{ $address->id }}" 
                                                   class="mt-1 w-5 h-5 saved-billing-address-radio"
                                                   data-street="{{ $address->street }}"
                                                   data-city="{{ $address->city }}"
                                                   data-state="{{ $address->state }}"
                                                   data-postal-code="{{ $address->postal_code }}">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-title font-semibold">{{ $address->label }}</span>
                                                    @if($address->is_default)
                                                        <span class="caption2 bg-green text-white px-2 py-1 rounded">Default</span>
                                                    @endif
                                                </div>
                                                <div class="caption2 text-secondary">
                                                    {{ $address->street }}, {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                                </div>
                                            </div>
                                        </label>
                                        @endforeach
                                        <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors">
                                            <input type="radio" name="saved_billing_address_id" value="" class="mt-1 w-5 h-5 saved-billing-address-radio" id="use_new_billing_address">
                                            <div class="flex-1">
                                                <span class="text-title font-semibold">Use New Address</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <div id="billing_address_input_fields" class="{{ $savedAddresses->count() > 0 ? 'hidden' : '' }}">
                                    <div class="mb-4">
                                        <label for="billing_street" class="caption2 text-secondary block mb-2">Street Address *</label>
                                        <input type="text" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line @error('billing_address.street') border-red @enderror" 
                                               id="billing_street" name="billing_address[street]" value="{{ old('billing_address.street') }}">
                                        @error('billing_address.street')
                                            <div class="caption2 text-red mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <label for="billing_state" class="caption2 text-secondary block mb-2">State/Province *</label>
                                            <input type="text" 
                                                   class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line location-search-input" 
                                                   id="billing_state" 
                                                   name="billing_address[state]" 
                                                   list="billing_state_list"
                                                   placeholder="Search or select state"
                                                   autocomplete="off"
                                                   value="{{ old('billing_address.state') }}">
                                            <datalist id="billing_state_list"></datalist>
                                            @error('billing_address.state')
                                                <div class="caption2 text-red mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="billing_city" class="caption2 text-secondary block mb-2">City *</label>
                                            <input type="text" 
                                                   class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line location-search-input" 
                                                   id="billing_city" 
                                                   name="billing_address[city]" 
                                                   list="billing_city_list"
                                                   placeholder="Search or select city"
                                                   autocomplete="off"
                                                   value="{{ old('billing_address.city') }}">
                                            <datalist id="billing_city_list"></datalist>
                                            @error('billing_address.city')
                                                <div class="caption2 text-red mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="billing_postal_code" class="caption2 text-secondary block mb-2">Postal Code *</label>
                                            <input type="text" 
                                                   class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" 
                                                   id="billing_postal_code" 
                                                   name="billing_address[postal_code]" 
                                                   placeholder="Postal code"
                                                   value="{{ old('billing_address.postal_code') }}">
                                            @error('billing_address.postal_code')
                                                <div class="caption2 text-red mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <input type="hidden" name="billing_address[country]" value="Saudi Arabia">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="bg-white rounded-2xl mb-6 overflow-hidden">
                        <div class="p-6 border-b border-line">
                            <h5 class="heading6 text-black">Shipping Method</h5>
                        </div>
                        <div class="p-6">
                            @if($shippingMethods->count() > 0)
                                @foreach($shippingMethods as $method)
                                    <div class="mb-4">
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input class="shipping-method-radio w-5 h-5" type="radio" name="shipping_method_id" 
                                                   id="shipping_method_{{ $method->id }}" value="{{ $method->id }}" 
                                                   data-cost="{{ $method->cost }}"
                                                   data-threshold="{{ $method->free_shipping_threshold ?? 0 }}"
                                                   {{ (old('shipping_method_id', $selectedShipping?->id) == $method->id) ? 'checked' : '' }} required>
                                            <div class="flex-1">
                                                <span class="text-title font-semibold">{{ $method->name }}</span>
                                                @if($method->free_shipping_threshold)
                                                    <span class="caption1 text-secondary"> - Free shipping on orders over {{ \App\Services\CurrencyService::format($method->free_shipping_threshold) }}</span>
                                                @endif
                                            </div>
                                            <span class="text-title font-semibold" id="shipping_cost_{{ $method->id }}">
                                                @if($method->free_shipping_threshold && $subtotal >= $method->free_shipping_threshold)
                                                    Free
                                                @else
                                                    {{ \App\Services\CurrencyService::format($method->cost) }}
                                                @endif
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p class="caption1 text-secondary">No shipping methods available. Please contact support.</p>
                            @endif
                            @error('shipping_method_id')
                                <div class="caption2 text-red mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-2xl mb-6 overflow-hidden">
                        <div class="p-6 border-b border-line">
                            <h5 class="heading6 text-black">Payment Method</h5>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-5 h-5" type="radio" name="payment_method" 
                                           id="cash_on_delivery" value="cash_on_delivery" 
                                           {{ old('payment_method', 'cash_on_delivery') == 'cash_on_delivery' ? 'checked' : '' }} required>
                                    <span class="caption1">Cash on Delivery</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-5 h-5" type="radio" name="payment_method" 
                                           id="credit_card" value="credit_card" 
                                           {{ old('payment_method') == 'credit_card' ? 'checked' : '' }}>
                                    <span class="caption1">Credit Card</span>
                                </label>
                            </div>
                            <div class="mb-0">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-5 h-5" type="radio" name="payment_method" 
                                           id="paypal" value="paypal" 
                                           {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                    <span class="caption1">PayPal</span>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="caption2 text-red mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-2xl mb-6 overflow-hidden">
                        <div class="p-6 border-b border-line">
                            <h5 class="heading6 text-black">Order Notes (Optional)</h5>
                        </div>
                        <div class="p-6">
                            <textarea class="caption1 w-full pl-4 pr-4 pt-3 pb-3 rounded-xl border border-line" name="notes" rows="3" 
                                      placeholder="Any special instructions for your order...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-between items-center gap-4">
                        <a href="{{ route('cart.index') }}" class="button-main bg-white text-black border border-black">
                            Back to Cart
                        </a>
                        <button type="submit" class="button-main bg-green text-black hover:bg-white">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl overflow-hidden sticky top-6">
                    <div class="p-6 border-b border-line">
                        <h5 class="heading6 text-black">Order Summary</h5>
                    </div>
                    <div class="p-6">
                        @foreach($cart as $item)
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-line last:border-0 last:pb-0">
                            <div>
                                <h6 class="text-title mb-1">{{ $item['name'] }}</h6>
                                <small class="caption2 text-secondary">Qty: {{ $item['quantity'] }}</small>
                            </div>
                            <span class="text-title font-semibold">{{ \App\Services\CurrencyService::format($item['price'] * $item['quantity']) }}</span>
                        </div>
                        @endforeach
                        
                        <div class="border-t border-line pt-4 mt-4">
                            <div class="flex justify-between mb-3">
                                <span class="caption1 text-secondary">Subtotal:</span>
                                <span class="caption1 font-semibold" id="order_subtotal">{{ \App\Services\CurrencyService::format($subtotal) }}</span>
                            </div>
                            <div class="flex justify-between mb-3">
                                <span class="caption1 text-secondary">Tax:</span>
                                <span class="caption1 font-semibold" id="order_tax">{{ \App\Services\CurrencyService::format($taxAmount) }}</span>
                            </div>
                            <div class="flex justify-between mb-4">
                                <span class="caption1 text-secondary">Shipping:</span>
                                <span class="caption1 font-semibold" id="order_shipping">{{ \App\Services\CurrencyService::format($shippingAmount) }}</span>
                            </div>
                            <div class="border-t border-line pt-4 flex justify-between">
                                <strong class="heading6 text-black">Total:</strong>
                                <strong class="heading6 text-black" id="order_total">{{ \App\Services\CurrencyService::format($total) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = {{ $subtotal }};
    const taxAmount = {{ $taxAmount }};
    const shippingRadios = document.querySelectorAll('.shipping-method-radio');
    
    // Load states on page load
    loadStates();
    
    // Handle saved shipping address selection
    document.querySelectorAll('.saved-address-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value) {
                // Fill fields from saved address
                document.getElementById('shipping_street').value = this.dataset.street || '';
                document.getElementById('shipping_state').value = this.dataset.state || '';
                document.getElementById('shipping_city').value = this.dataset.city || '';
                document.getElementById('shipping_postal_code').value = this.dataset.postalCode || '';
                // Hide manual input fields
                document.getElementById('shipping_address_fields').classList.add('hidden');
            } else {
                // Show manual input fields
                document.getElementById('shipping_address_fields').classList.remove('hidden');
            }
        });
    });
    
    // Handle saved billing address selection
    document.querySelectorAll('.saved-billing-address-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value) {
                document.getElementById('billing_street').value = this.dataset.street || '';
                document.getElementById('billing_state').value = this.dataset.state || '';
                document.getElementById('billing_city').value = this.dataset.city || '';
                document.getElementById('billing_postal_code').value = this.dataset.postalCode || '';
                document.getElementById('billing_address_input_fields').classList.add('hidden');
            } else {
                document.getElementById('billing_address_input_fields').classList.remove('hidden');
            }
        });
    });
    
    // Handle "Same as shipping" checkbox
    const sameAsShipping = document.getElementById('same_as_shipping');
    const billingFields = document.getElementById('billing_address_fields');
    sameAsShipping.addEventListener('change', function() {
        if (this.checked) {
            billingFields.classList.add('hidden');
            // Clear billing address fields when same as shipping
            document.getElementById('billing_street').value = '';
            document.getElementById('billing_state').value = '';
            document.getElementById('billing_city').value = '';
            document.getElementById('billing_postal_code').value = '';
            // Uncheck any saved billing address
            document.querySelectorAll('input[name="saved_billing_address_id"]').forEach(radio => {
                radio.checked = false;
            });
        } else {
            billingFields.classList.remove('hidden');
        }
    });
    
    // Location search functionality
    let statesData = [];
    let citiesData = [];
    
    function loadStates() {
        fetch('{{ route("api.saudi-arabia.states") }}')
            .then(response => response.json())
            .then(data => {
                statesData = data;
                const stateList = document.getElementById('shipping_state_list');
                const billingStateList = document.getElementById('billing_state_list');
                stateList.innerHTML = '';
                billingStateList.innerHTML = '';
                data.forEach(state => {
                    const option1 = document.createElement('option');
                    option1.value = state;
                    stateList.appendChild(option1);
                    const option2 = document.createElement('option');
                    option2.value = state;
                    billingStateList.appendChild(option2);
                });
            })
            .catch(error => console.error('Error loading states:', error));
    }
    
    // Load cities when state changes
    document.getElementById('shipping_state').addEventListener('input', function() {
        const state = this.value;
        if (state && statesData.includes(state)) {
            loadCities(state, 'shipping');
        }
    });
    
    document.getElementById('billing_state').addEventListener('input', function() {
        const state = this.value;
        if (state && statesData.includes(state)) {
            loadCities(state, 'billing');
        }
    });
    
    function loadCities(state, type) {
        fetch(`{{ route("api.saudi-arabia.cities") }}?state=${encodeURIComponent(state)}`)
            .then(response => response.json())
            .then(data => {
                citiesData = data;
                const cityList = document.getElementById(`${type}_city_list`);
                cityList.innerHTML = '';
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    cityList.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading cities:', error));
    }
    
    // Auto-fill postal code when city is selected
    document.getElementById('shipping_city').addEventListener('input', function() {
        const city = this.value;
        const state = document.getElementById('shipping_state').value;
        if (city && state) {
            loadPostalCode(city, state, 'shipping');
        }
    });
    
    document.getElementById('billing_city').addEventListener('input', function() {
        const city = this.value;
        const state = document.getElementById('billing_state').value;
        if (city && state) {
            loadPostalCode(city, state, 'billing');
        }
    });
    
    function loadPostalCode(city, state, type) {
        fetch(`{{ route("api.saudi-arabia.postal-codes") }}?city=${encodeURIComponent(city)}&state=${encodeURIComponent(state)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    document.getElementById(`${type}_postal_code`).value = data[0];
                }
            })
            .catch(error => console.error('Error loading postal codes:', error));
    }
    
    // Form validation - ensure required fields are filled
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const savedShippingId = document.querySelector('input[name="saved_shipping_address_id"]:checked')?.value;
        const savedBillingId = document.querySelector('input[name="saved_billing_address_id"]:checked')?.value;
        const sameAsShippingChecked = document.getElementById('same_as_shipping').checked;
        
        // Validate shipping address
        if (!savedShippingId) {
            const shippingStreet = document.getElementById('shipping_street').value.trim();
            const shippingState = document.getElementById('shipping_state').value.trim();
            const shippingCity = document.getElementById('shipping_city').value.trim();
            const shippingPostal = document.getElementById('shipping_postal_code').value.trim();
            
            if (!shippingStreet || !shippingState || !shippingCity || !shippingPostal) {
                e.preventDefault();
                alert('Please fill in all shipping address fields or select a saved address.');
                return false;
            }
        }
        
        // Validate billing address
        if (!sameAsShippingChecked) {
            if (!savedBillingId) {
                const billingStreet = document.getElementById('billing_street').value.trim();
                const billingState = document.getElementById('billing_state').value.trim();
                const billingCity = document.getElementById('billing_city').value.trim();
                const billingPostal = document.getElementById('billing_postal_code').value.trim();
                
                if (!billingStreet || !billingState || !billingCity || !billingPostal) {
                    e.preventDefault();
                    alert('Please fill in all billing address fields or select a saved address.');
                    return false;
                }
            }
        }
    });
    
    function updateOrderSummary() {
        const selectedShipping = document.querySelector('.shipping-method-radio:checked');
        if (!selectedShipping) return;
        
        const shippingCost = parseFloat(selectedShipping.dataset.cost) || 0;
        const threshold = parseFloat(selectedShipping.dataset.threshold) || 0;
        
        // Check if free shipping threshold is met
        const finalShippingCost = (threshold > 0 && subtotal >= threshold) ? 0 : shippingCost;
        
        // Update shipping display
        const shippingCostElement = document.getElementById('order_shipping');
        if (shippingCostElement) {
            shippingCostElement.textContent = finalShippingCost === 0 ? 'Free' : '{{ \App\Services\CurrencyService::code() }}' + finalShippingCost.toFixed(2);
        }
        
        // Calculate and update total
        const total = subtotal + taxAmount + finalShippingCost;
        const totalElement = document.getElementById('order_total');
        if (totalElement) {
            totalElement.textContent = '{{ \App\Services\CurrencyService::code() }}' + total.toFixed(2);
        }
    }
    
    // Update when shipping method changes
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', updateOrderSummary);
    });
    
    // Initial update
    updateOrderSummary();
});
</script>
@endpush
@endsection
