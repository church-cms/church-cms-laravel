@extends('layouts.donateempty')

@section('title', 'Donate')

@push('styles')
<style>
    [x-cloak] {
        display: none !important;
    }

    .gateway-btn {
        transition: all .15s;
    }

    .gateway-btn.selected {
        border-color: #4f46e5;
        background: #eef2ff;
    }

    .amount-btn {
        transition: all .15s;
    }

    .amount-btn.selected {
        border-color: #4f46e5;
        background: #eef2ff;
        color: #4338ca;
    }
</style>
@endpush

@section('content')

@php
$gatewayIcons = [
'paystack' => '💳',
'flutterwave' => '🦋',
'mpesa' => '📱',
'gcash' => '💙',
'pix' => '🇧🇷',
'telebirr' => '🇪🇹',
'bank' => '🏦',
'cash' => '💵',
'cheque' => '📝',
'gpay' => '🔵',
'upi' => '⚡',
'stripe' => '🔒',
];

@endphp

<div class="max-w-2xl mx-auto" x-data="donationApp()" x-init="boot()">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Give / Donate</h1>
        <p class="text-sm text-gray-500 mt-1">Your generosity makes a difference. Every gift is appreciated.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        {{-- ── STEP 1: Amount ──────────────────────────────────────── --}}
        <div class="mb-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Select Amount</p>
            <div class="grid grid-cols-3 gap-2 mb-3">
                @foreach([100, 200, 300, 400, 500, 1000, 1500, 2000, 2500] as $preset)
                <button type="button"
                    class="amount-btn border-2 border-gray-200 rounded-xl py-3 text-sm font-semibold text-gray-700 hover:border-indigo-400 focus:outline-none"
                    x-bind:class="{ 'selected': amount === {{ $preset }} }"
                    x-on:click="pickAmount({{ $preset }})">
                    {{ number_format($preset) }}
                </button>
                @endforeach
                <button type="button"
                    class="amount-btn border-2 border-gray-200 rounded-xl py-3 text-sm font-semibold text-gray-700 hover:border-indigo-400 focus:outline-none"
                    x-bind:class="{ 'selected': amount === 'other' }"
                    x-on:click="pickAmount('other')">
                    Other
                </button>
            </div>
            <div x-show="amount === 'other'" x-cloak>
                <input type="number" x-model="customAmount" min="1"
                    x-on:input="customAmountError = ''"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Enter amount">
            </div>
            <p x-show="amountError" x-cloak class="text-red-500 text-xs mt-1" x-text="amountError"></p>
            <div x-show="amount === 'other' && customAmountError" x-cloak>
                <p class="text-red-500 text-xs mt-1" x-text="customAmountError"></p>
            </div>
        </div>

        {{-- ── STEP 2: Type + Note ──────────────────────────────────── --}}
        <div class="mb-6 grid gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Donation Type</label>
                <select x-model="category"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="offering">Offering</option>
                    <option value="tithe">Tithe</option>
                    <option value="building">Building Fund</option>
                    <option value="missions">Missions</option>
                    <option value="welfare">Welfare</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">
                    Note <span class="font-normal normal-case text-gray-400">(optional)</span>
                </label>
                <textarea x-model="note" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none"
                    placeholder="Any message with your donation…"></textarea>
            </div>
        </div>

        {{-- ── STEP 3: Payment Method ───────────────────────────────── --}}
        @if(count($payaccounts) > 0)
        <div class="mb-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Payment Method</p>
            <div class="space-y-2">
                @foreach($payaccounts as $pa)
                <button type="button"
                    class="gateway-btn w-full flex items-center gap-3 border-2 border-gray-200 rounded-xl px-4 py-3 text-left focus:outline-none"
                    x-bind:class="{ 'selected': selectedId === {{ $pa['id'] }} }"
                    x-on:click="selectGateway({{ $pa['id'] }})">
                    <span class="text-xl">{{ $gatewayIcons[$pa['gatewayname']] ?? '💰' }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">{{ $pa['display_name'] }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $pa['instructions'] }}</p>
                    </div>
                    <svg x-show="selectedId === {{ $pa['id'] }}" class="w-5 h-5 text-indigo-600 flex-shrink-0"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                @endforeach
            </div>
            <p x-show="gatewayError" x-cloak class="text-red-500 text-xs mt-2" x-text="gatewayError"></p>

            {{-- M-Pesa phone --}}
            <div class="mt-4" x-show="gatewayName() === 'mpesa'" x-cloak>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">
                    M-Pesa Phone Number
                </label>
                <input type="tel" x-model="mpesaPhone"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="e.g. 0712345678">
            </div>

            {{-- Stripe card element --}}
            <div class="mt-4" x-show="gatewayName() === 'stripe'" x-cloak>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                    Card Details
                </label>
                <div id="stripe-card-element"
                    class="border border-gray-300 rounded-lg px-4 py-3 bg-white text-sm focus-within:ring-2 focus-within:ring-indigo-400">
                    {{-- Stripe.js mounts here --}}
                </div>
                <p id="stripe-card-errors" class="text-red-500 text-xs mt-1" style="display:none;"></p>
            </div>

            {{-- Offline instructions --}}
            <div class="mt-4 p-3 bg-gray-50 rounded-lg text-sm text-gray-700"
                x-show="selectedId && !isOnline() && gatewayName() !== 'stripe'" x-cloak>
                <p class="font-medium mb-1">Payment Instructions</p>
                <p class="text-gray-500" x-text="selectedInstructions()"></p>
            </div>
        </div>
        @else
        <div class="mb-6 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
            No payment methods configured for your church yet. Please contact the administrator.
        </div>
        @endif

        {{-- Amount preview --}}
        <div class="mb-5 p-3 bg-indigo-50 rounded-lg text-center" x-show="finalAmount() > 0" x-cloak>
            <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide">You are donating</p>
            <p class="text-2xl font-bold text-indigo-700 mt-0.5" x-text="finalAmount().toLocaleString()"></p>
        </div>

        {{-- M-Pesa feedback --}}
        <div class="mb-4 p-3 rounded-lg text-sm font-medium text-center" x-show="statusMsg" x-cloak
            x-bind:class="statusErr ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-green-50 text-green-700 border border-green-200'"
            x-text="statusMsg">
        </div>

        {{-- Donate button --}}
        <button type="button" x-on:click="submit()"
            x-bind:disabled="!canSubmit() || loading"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl py-3 text-sm transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span x-text="loading ? 'Processing…' : 'Donate Now'"></span>
        </button>

        {{-- Hidden forms --}}
        <form id="form-offline" method="POST" action="{{ route('donate.store') }}" style="display:none">
            @csrf
            <input type="hidden" name="amount" id="fo-amount">
            <input type="hidden" name="category" id="fo-category">
            <input type="hidden" name="note" id="fo-note">
            <input type="hidden" name="payaccount_id" id="fo-pid">
        </form>

        <form id="form-verify" method="POST" action="{{ route('donate.verify') }}" style="display:none">
            @csrf
            <input type="hidden" name="gateway" id="fv-gateway">
            <input type="hidden" name="reference" id="fv-reference">
            <input type="hidden" name="payaccount_id" id="fv-pid">
            <input type="hidden" name="amount" id="fv-amount">
            <input type="hidden" name="category" id="fv-category">
            <input type="hidden" name="church_id" id="fv-churchid">
            <input type="hidden" name="note" id="fv-note">
        </form>

    </div>{{-- /card --}}

    {{-- ── HISTORY ──────────────────────────────────────────────────── --}}
    @if($donations->count() > 0)
    <div class="mt-8">
        <h2 class="text-base font-semibold text-gray-700 mb-3">My Donation History</h2>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 divide-y divide-gray-50">
            @foreach($donations as $d)
            <div class="flex items-center justify-between px-5 py-3.5">
                <div>
                    <p class="text-sm font-semibold text-gray-800 capitalize">{{ $d->category }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ ($d->donated_at ?? $d->created_at)->format('d M Y') }}
                        &bull; {{ ucfirst($d->method) }}
                        @if($d->note) &bull; {{ Str::limit($d->note, 40) }} @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-indigo-600">{{ $d->currency }} {{ number_format($d->amount, 2) }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium
                        {{ $d->status === 'completed' ? 'bg-green-100 text-green-700'
                          : ($d->status === 'cancelled' ? 'bg-red-100 text-red-600'
                          : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($d->status) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
    var _stripeInstance = null;
    var _cardElement = null;

    function donationApp() {
        var payaccounts = @json($payaccounts);

        return {
            payaccounts: payaccounts,
            amount: null,
            customAmount: '',
            category: 'offering',
            note: '',
            selectedId: payaccounts.length === 1 ? payaccounts[0].id : null,
            mpesaPhone: '',
            loading: false,
            statusMsg: '',
            statusErr: false,
            amountError: '',
            customAmountError: '',
            gatewayError: '',
            stripeReady: false,

            boot() {
                if (this.selectedId) {
                    var gw = this.selectedGateway();
                    if (gw && gw.gatewayname === 'stripe') {
                        var self = this;
                        this.$nextTick(function() {
                            self.mountStripeCard(gw.public_key);
                        });
                    }
                }
            },

            mountStripeCard(publicKey) {
                this.stripeReady = false;
                if (!publicKey || typeof Stripe === 'undefined') return;
                var el = document.getElementById('stripe-card-element');
                if (!el) return;

                if (_stripeInstance && _cardElement) {
                    _cardElement.destroy();
                    _cardElement = null;
                }
                _stripeInstance = Stripe(publicKey);
                var elements = _stripeInstance.elements();
                _cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '14px',
                            color: '#374151',
                            '::placeholder': {
                                color: '#9CA3AF'
                            }
                        },
                        invalid: {
                            color: '#EF4444'
                        },
                    },
                });
                _cardElement.mount('#stripe-card-element');
                _cardElement.on('change', function(event) {
                    var errEl = document.getElementById('stripe-card-errors');
                    if (event.error) {
                        errEl.textContent = event.error.message;
                        errEl.style.display = 'block';
                    } else {
                        errEl.textContent = '';
                        errEl.style.display = 'none';
                    }
                });
                this.stripeReady = true;
            },

            unmountStripeCard() {
                if (_cardElement) {
                    _cardElement.destroy();
                    _cardElement = null;
                }
                _stripeInstance = null;
                this.stripeReady = false;
            },

            pickAmount(val) {
                this.amount = val;
                this.amountError = '';
            },

            finalAmount() {
                if (this.amount === 'other') return parseFloat(this.customAmount) || 0;
                return parseFloat(this.amount) || 0;
            },

            selectedGateway() {
                if (!this.selectedId) return null;
                return this.payaccounts.find(function(p) {
                    return p.id === this.selectedId;
                }, this) || null;
            },

            gatewayName() {
                var gw = this.selectedGateway();
                return gw ? gw.gatewayname : '';
            },

            isOnline() {
                var gw = this.selectedGateway();
                return gw ? gw.is_online : false;
            },

            selectedInstructions() {
                var gw = this.selectedGateway();
                return gw ? gw.instructions : '';
            },

            selectGateway(id) {
                this.selectedId = id;
                this.gatewayError = '';
                this.statusMsg = '';
                this.statusErr = false;
                var gw = this.selectedGateway();
                var self = this;
                if (gw && gw.gatewayname === 'stripe') {
                    this.$nextTick(function() {
                        self.mountStripeCard(gw.public_key);
                    });
                } else {
                    this.unmountStripeCard();
                }
            },

            canSubmit() {
                if (this.finalAmount() < 1) return false;
                if (this.payaccounts.length > 0 && !this.selectedId) return false;
                if (this.gatewayName() === 'mpesa' && !this.mpesaPhone.trim()) return false;
                if (this.gatewayName() === 'stripe' && !this.stripeReady) return false;
                return true;
            },

            submit() {
                this.amountError = '';
                this.customAmountError = '';
                this.gatewayError = '';

                if (!this.amount) {
                    this.amountError = 'Please select an amount.';
                    return;
                }
                if (this.amount === 'other' && !(parseFloat(this.customAmount) > 0)) {
                    this.customAmountError = 'Please enter a valid amount.';
                    return;
                }
                if (this.finalAmount() < 1) {
                    this.amountError = 'Amount must be at least 1.';
                    return;
                }
                if (this.payaccounts.length > 0 && !this.selectedId) {
                    this.gatewayError = 'Please select a payment method.';
                    return;
                }
                var gw = this.selectedGateway();
                if (!gw) {
                    this.submitOffline(null);
                    return;
                }

                if (gw.gatewayname === 'paystack') {
                    this.payWithPaystack(gw);
                    return;
                }
                if (gw.gatewayname === 'flutterwave') {
                    this.payWithFlutterwave(gw);
                    return;
                }
                if (gw.gatewayname === 'mpesa') {
                    this.payWithMpesa(gw);
                    return;
                }
                if (gw.gatewayname === 'stripe') {
                    this.payWithStripe(gw);
                    return;
                }
                if (gw.gatewayname === 'gcash') {
                    this.payWithGCash(gw);
                    return;
                }
                this.submitOffline(gw.id);
            },

            submitOffline(pid) {
                document.getElementById('fo-amount').value = this.finalAmount();
                document.getElementById('fo-category').value = this.category;
                document.getElementById('fo-note').value = this.note;
                document.getElementById('fo-pid').value = pid || '';
                document.getElementById('form-offline').submit();
            },

            submitVerify(gateway, reference, pid) {
                document.getElementById('fv-gateway').value = gateway;
                document.getElementById('fv-reference').value = reference;
                document.getElementById('fv-pid').value = pid;
                document.getElementById('fv-amount').value = this.finalAmount();
                document.getElementById('fv-category').value = this.category;
                document.getElementById('fv-note').value = this.note;
                document.getElementById('fv-churchid').value = '{{request('church_id')}}';
                document.getElementById('form-verify').submit();
            },

            payWithPaystack(gw) {
                var self = this;
                var handler = PaystackPop.setup({
                    key: gw.public_key,
                    email: '{{ $user->email }}',
                    amount: Math.round(self.finalAmount() * 100),

                    currency: gw.currency || 'NGN',
                    ref: 'DON-' + Date.now(),
                    callback: function(response) {
                        self.submitVerify('paystack', response.reference, gw.id);
                    },
                    onClose: function() {},
                });
                handler.openIframe();
            },

            payWithFlutterwave(gw) {
                var self = this;
                FlutterwaveCheckout({
                    public_key: gw.public_key,
                    tx_ref: 'DON-' + Date.now(),
                    amount: self.finalAmount(),
                    currency: gw.currency || 'NGN',
                    customer: {
                        email: '{{ $user->email }}',
                        name: '{{ trim(optional(Auth::user()->userprofile)->firstname . " " . optional(Auth::user()->userprofile)->lastname) ?: Auth::user()->name }}',
                    },
                    customizations: {
                        title: 'Church Donation',
                        description: self.category,
                    },
                    callback: function(data) {
                        if (data.status === 'successful') {
                            self.submitVerify('flutterwave', data.transaction_id, gw.id);
                        }
                    },
                    onclose: function() {},
                });
            },

            payWithStripe(gw) {
                if (!_stripeInstance || !_cardElement) {
                    this.statusMsg = 'Stripe is not ready. Please try again.';
                    this.statusErr = true;
                    return;
                }
                this.loading = true;
                this.statusMsg = '';
                this.statusErr = false;
                var self = this;

                fetch('{{ route("donate.stripe-intent") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            amount: self.finalAmount(),
                            payaccount_id: gw.id,
                            category: self.category,
                            note: self.note,
                        }),
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        if (!data.client_secret) {
                            self.loading = false;
                            self.statusMsg = data.error || 'Could not create payment.';
                            self.statusErr = true;
                            return;
                        }
                        return _stripeInstance.confirmCardPayment(data.client_secret, {
                            payment_method: {
                                card: _cardElement
                            },
                        }).then(function(result) {
                            self.loading = false;
                            if (result.error) {
                                var errEl = document.getElementById('stripe-card-errors');
                                errEl.textContent = result.error.message;
                                errEl.style.display = 'block';
                            } else if (result.paymentIntent && result.paymentIntent.status === 'succeeded') {
                                self.submitVerify('stripe', result.paymentIntent.id, gw.id);
                            }
                        });
                    })
                    .catch(function() {
                        self.loading = false;
                        self.statusMsg = 'Network error. Please try again.';
                        self.statusErr = true;
                    });
            },

            payWithMpesa(gw) {
                if (!this.mpesaPhone.trim()) {
                    this.statusMsg = 'Please enter your M-Pesa phone number.';
                    this.statusErr = true;
                    return;
                }
                this.loading = true;
                this.statusMsg = '';
                this.statusErr = false;
                var self = this;

                fetch('{{ route("donate.mpesa-stk") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            phone: self.mpesaPhone,
                            amount: self.finalAmount(),
                            payaccount_id: gw.id,
                            category: self.category,
                            note: self.note,
                        }),
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        self.loading = false;
                        if (data.success) {
                            self.statusMsg = data.message;
                            self.statusErr = false;
                        } else {
                            self.statusMsg = data.error || 'M-Pesa request failed.';
                            self.statusErr = true;
                        }
                    })
                    .catch(function() {
                        self.loading = false;
                        self.statusMsg = 'Network error. Please try again.';
                        self.statusErr = true;
                    });
            },

            payWithGCash(gw) {
                this.loading = true;
                this.statusMsg = '';
                this.statusErr = false;
                var self = this;

                fetch('{{ route("donate.gcash-init") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            amount: self.finalAmount(),
                            payaccount_id: gw.id,
                            category: self.category,
                            note: self.note,
                        }),
                    })
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(data) {
                        if (data.checkout_url) {
                            window.location.href = data.checkout_url;
                        } else {
                            self.loading = false;
                            self.statusMsg = data.error || 'Could not initiate GCash payment.';
                            self.statusErr = true;
                        }
                    })
                    .catch(function() {
                        self.loading = false;
                        self.statusMsg = 'Network error. Please try again.';
                        self.statusErr = true;
                    });
            },
        };
    }
</script>
@endpush