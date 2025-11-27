

<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginala9d931d4f11b4d2850df99e991db1dca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9d931d4f11b4d2850df99e991db1dca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-hero','data' => ['breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Cart', 'url' => route('cart.index')],
            ['label' => 'Checkout']
        ],'eyebrow' => 'Complete your order','title' => 'Checkout','description' => 'Review your order details and complete your purchase.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Cart', 'url' => route('cart.index')],
            ['label' => 'Checkout']
        ]),'eyebrow' => 'Complete your order','title' => 'Checkout','description' => 'Review your order details and complete your purchase.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9d931d4f11b4d2850df99e991db1dca)): ?>
<?php $attributes = $__attributesOriginala9d931d4f11b4d2850df99e991db1dca; ?>
<?php unset($__attributesOriginala9d931d4f11b4d2850df99e991db1dca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9d931d4f11b4d2850df99e991db1dca)): ?>
<?php $component = $__componentOriginala9d931d4f11b4d2850df99e991db1dca; ?>
<?php unset($__componentOriginala9d931d4f11b4d2850df99e991db1dca); ?>
<?php endif; ?>

<section class="py-10 md:py-16">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8">
                <h2 class="heading3 text-black mb-6">Checkout</h2>
                
                <form action="<?php echo e(route('orders.store')); ?>" method="POST" id="checkout-form">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Shipping Address -->
                    <div class="bg-white rounded-2xl mb-6 overflow-hidden">
                        <div class="p-6 border-b border-line">
                            <h5 class="heading6 text-black">Shipping Address</h5>
                        </div>
                        <div class="p-6">
                            <?php if($savedAddresses->count() > 0): ?>
                            <div class="mb-6">
                                <label class="caption2 text-secondary block mb-3">Use Saved Address</label>
                                <div class="space-y-3">
                                    <?php $__currentLoopData = $savedAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors saved-address-option">
                                        <input type="radio" name="saved_shipping_address_id" value="<?php echo e($address->id); ?>" 
                                               class="mt-1 w-5 h-5 saved-address-radio" 
                                               data-street="<?php echo e($address->street); ?>"
                                               data-city="<?php echo e($address->city); ?>"
                                               data-state="<?php echo e($address->state); ?>"
                                               data-postal-code="<?php echo e($address->postal_code); ?>"
                                               <?php echo e(old('saved_shipping_address_id', $defaultAddress?->id) == $address->id ? 'checked' : ''); ?>>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-title font-semibold"><?php echo e($address->label); ?></span>
                                                <?php if($address->is_default): ?>
                                                    <span class="caption2 bg-green text-white px-2 py-1 rounded">Default</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="caption2 text-secondary">
                                                <?php echo e($address->street); ?>, <?php echo e($address->city); ?>, <?php echo e($address->state); ?> <?php echo e($address->postal_code); ?>

                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors">
                                        <input type="radio" name="saved_shipping_address_id" value="" class="mt-1 w-5 h-5 saved-address-radio" id="use_new_shipping_address">
                                        <div class="flex-1">
                                            <span class="text-title font-semibold">Use New Address</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div id="shipping_address_fields" class="<?php echo e($savedAddresses->count() > 0 && $defaultAddress ? 'hidden' : ''); ?>">
                                <div class="mb-4">
                                    <label for="shipping_street" class="caption2 text-secondary block mb-2">Street Address *</label>
                                    <input type="text" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line <?php $__errorArgs = ['shipping_address.street'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_street" name="shipping_address[street]" value="<?php echo e(old('shipping_address.street', $defaultAddress->street ?? '')); ?>">
                                    <?php $__errorArgs = ['shipping_address.street'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                               value="<?php echo e(old('shipping_address.state', $defaultAddress->state ?? '')); ?>">
                                        <datalist id="shipping_state_list"></datalist>
                                        <?php $__errorArgs = ['shipping_address.state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                               value="<?php echo e(old('shipping_address.city', $defaultAddress->city ?? '')); ?>">
                                        <datalist id="shipping_city_list"></datalist>
                                        <?php $__errorArgs = ['shipping_address.city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div>
                                        <label for="shipping_postal_code" class="caption2 text-secondary block mb-2">Postal Code *</label>
                                        <input type="text" 
                                               class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" 
                                               id="shipping_postal_code" 
                                               name="shipping_address[postal_code]" 
                                               placeholder="Postal code"
                                               value="<?php echo e(old('shipping_address.postal_code', $defaultAddress->postal_code ?? '')); ?>">
                                        <?php $__errorArgs = ['shipping_address.postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                <?php if($savedAddresses->count() > 0): ?>
                                <div class="mb-6">
                                    <label class="caption2 text-secondary block mb-3">Use Saved Address</label>
                                    <div class="space-y-3">
                                        <?php $__currentLoopData = $savedAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors">
                                            <input type="radio" name="saved_billing_address_id" value="<?php echo e($address->id); ?>" 
                                                   class="mt-1 w-5 h-5 saved-billing-address-radio"
                                                   data-street="<?php echo e($address->street); ?>"
                                                   data-city="<?php echo e($address->city); ?>"
                                                   data-state="<?php echo e($address->state); ?>"
                                                   data-postal-code="<?php echo e($address->postal_code); ?>">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-title font-semibold"><?php echo e($address->label); ?></span>
                                                    <?php if($address->is_default): ?>
                                                        <span class="caption2 bg-green text-white px-2 py-1 rounded">Default</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="caption2 text-secondary">
                                                    <?php echo e($address->street); ?>, <?php echo e($address->city); ?>, <?php echo e($address->state); ?> <?php echo e($address->postal_code); ?>

                                                </div>
                                            </div>
                                        </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-start gap-3 p-4 border border-line rounded-xl cursor-pointer hover:border-green transition-colors">
                                            <input type="radio" name="saved_billing_address_id" value="" class="mt-1 w-5 h-5 saved-billing-address-radio" id="use_new_billing_address">
                                            <div class="flex-1">
                                                <span class="text-title font-semibold">Use New Address</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div id="billing_address_input_fields" class="<?php echo e($savedAddresses->count() > 0 ? 'hidden' : ''); ?>">
                                    <div class="mb-4">
                                        <label for="billing_street" class="caption2 text-secondary block mb-2">Street Address *</label>
                                        <input type="text" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line <?php $__errorArgs = ['billing_address.street'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="billing_street" name="billing_address[street]" value="<?php echo e(old('billing_address.street')); ?>">
                                        <?php $__errorArgs = ['billing_address.street'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                                   value="<?php echo e(old('billing_address.state')); ?>">
                                            <datalist id="billing_state_list"></datalist>
                                            <?php $__errorArgs = ['billing_address.state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                                   value="<?php echo e(old('billing_address.city')); ?>">
                                            <datalist id="billing_city_list"></datalist>
                                            <?php $__errorArgs = ['billing_address.city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div>
                                            <label for="billing_postal_code" class="caption2 text-secondary block mb-2">Postal Code *</label>
                                            <input type="text" 
                                                   class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" 
                                                   id="billing_postal_code" 
                                                   name="billing_address[postal_code]" 
                                                   placeholder="Postal code"
                                                   value="<?php echo e(old('billing_address.postal_code')); ?>">
                                            <?php $__errorArgs = ['billing_address.postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="caption2 text-red mt-1"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            <?php if($shippingMethods->count() > 0): ?>
                                <?php $__currentLoopData = $shippingMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-4">
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input class="shipping-method-radio w-5 h-5" type="radio" name="shipping_method_id" 
                                                   id="shipping_method_<?php echo e($method->id); ?>" value="<?php echo e($method->id); ?>" 
                                                   data-cost="<?php echo e($method->cost); ?>"
                                                   data-threshold="<?php echo e($method->free_shipping_threshold ?? 0); ?>"
                                                   <?php echo e((old('shipping_method_id', $selectedShipping?->id) == $method->id) ? 'checked' : ''); ?> required>
                                            <div class="flex-1">
                                                <span class="text-title font-semibold"><?php echo e($method->name); ?></span>
                                                <?php if($method->free_shipping_threshold): ?>
                                                    <span class="caption1 text-secondary"> - Free shipping on orders over <?php echo e(\App\Services\CurrencyService::format($method->free_shipping_threshold)); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-title font-semibold" id="shipping_cost_<?php echo e($method->id); ?>">
                                                <?php if($method->free_shipping_threshold && $subtotal >= $method->free_shipping_threshold): ?>
                                                    Free
                                                <?php else: ?>
                                                    <?php echo e(\App\Services\CurrencyService::format($method->cost)); ?>

                                                <?php endif; ?>
                                            </span>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <p class="caption1 text-secondary">No shipping methods available. Please contact support.</p>
                            <?php endif; ?>
                            <?php $__errorArgs = ['shipping_method_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="caption2 text-red mt-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                           <?php echo e(old('payment_method', 'cash_on_delivery') == 'cash_on_delivery' ? 'checked' : ''); ?> required>
                                    <span class="caption1">Cash on Delivery</span>
                                </label>
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-5 h-5" type="radio" name="payment_method" 
                                           id="credit_card" value="credit_card" 
                                           <?php echo e(old('payment_method') == 'credit_card' ? 'checked' : ''); ?>>
                                    <span class="caption1">Credit Card</span>
                                </label>
                            </div>
                            <div class="mb-0">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-5 h-5" type="radio" name="payment_method" 
                                           id="paypal" value="paypal" 
                                           <?php echo e(old('payment_method') == 'paypal' ? 'checked' : ''); ?>>
                                    <span class="caption1">PayPal</span>
                                </label>
                            </div>
                            <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="caption2 text-red mt-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-2xl mb-6 overflow-hidden">
                        <div class="p-6 border-b border-line">
                            <h5 class="heading6 text-black">Order Notes (Optional)</h5>
                        </div>
                        <div class="p-6">
                            <textarea class="caption1 w-full pl-4 pr-4 pt-3 pb-3 rounded-xl border border-line" name="notes" rows="3" 
                                      placeholder="Any special instructions for your order..."><?php echo e(old('notes')); ?></textarea>
                        </div>
                    </div>

                    <div class="flex justify-between items-center gap-4">
                        <a href="<?php echo e(route('cart.index')); ?>" class="button-main bg-white text-black border border-black">
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
                        <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-line last:border-0 last:pb-0">
                            <div>
                                <h6 class="text-title mb-1"><?php echo e($item['name']); ?></h6>
                                <small class="caption2 text-secondary">Qty: <?php echo e($item['quantity']); ?></small>
                            </div>
                            <span class="text-title font-semibold"><?php echo e(\App\Services\CurrencyService::format($item['price'] * $item['quantity'])); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <div class="border-t border-line pt-4 mt-4">
                            <div class="flex justify-between mb-3">
                                <span class="caption1 text-secondary">Subtotal:</span>
                                <span class="caption1 font-semibold" id="order_subtotal"><?php echo e(\App\Services\CurrencyService::format($subtotal)); ?></span>
                            </div>
                            <div class="flex justify-between mb-3">
                                <span class="caption1 text-secondary">Tax:</span>
                                <span class="caption1 font-semibold" id="order_tax"><?php echo e(\App\Services\CurrencyService::format($taxAmount)); ?></span>
                            </div>
                            <div class="flex justify-between mb-4">
                                <span class="caption1 text-secondary">Shipping:</span>
                                <span class="caption1 font-semibold" id="order_shipping"><?php echo e(\App\Services\CurrencyService::format($shippingAmount)); ?></span>
                            </div>
                            <div class="border-t border-line pt-4 flex justify-between">
                                <strong class="heading6 text-black">Total:</strong>
                                <strong class="heading6 text-black" id="order_total"><?php echo e(\App\Services\CurrencyService::format($total)); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = <?php echo e($subtotal); ?>;
    const taxAmount = <?php echo e($taxAmount); ?>;
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
        fetch('<?php echo e(route("api.saudi-arabia.states")); ?>')
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
        fetch(`<?php echo e(route("api.saudi-arabia.cities")); ?>?state=${encodeURIComponent(state)}`)
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
        fetch(`<?php echo e(route("api.saudi-arabia.postal-codes")); ?>?city=${encodeURIComponent(city)}&state=${encodeURIComponent(state)}`)
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
            shippingCostElement.textContent = finalShippingCost === 0 ? 'Free' : '<?php echo e(\App\Services\CurrencyService::code()); ?>' + finalShippingCost.toFixed(2);
        }
        
        // Calculate and update total
        const total = subtotal + taxAmount + finalShippingCost;
        const totalElement = document.getElementById('order_total');
        if (totalElement) {
            totalElement.textContent = '<?php echo e(\App\Services\CurrencyService::code()); ?>' + total.toFixed(2);
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/orders/checkout.blade.php ENDPATH**/ ?>