<template>
    <div class="w-full lg:w-3/4">
        <div v-if="success" class="alert alert-success">{{ success }}</div>

        <div class="tw-form-group w-full">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Gateway Name (slug)<span class="text-red-500">*</span></label>
                </div>
                <input type="text" v-model="gatewayname" class="tw-form-control w-full" placeholder="e.g. stripe">
                <p v-if="errors.gatewayname" class="text-red-500 text-xs mt-1">{{ errors.gatewayname[0] }}</p>
            </div>
        </div>

        <div class="tw-form-group w-full mt-3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Display Name<span class="text-red-500">*</span></label>
                </div>
                <input type="text" v-model="displayname" class="tw-form-control w-full" placeholder="e.g. Stripe">
                <p v-if="errors.displayname" class="text-red-500 text-xs mt-1">{{ errors.displayname[0] }}</p>
            </div>
        </div>

        <div class="tw-form-group w-full mt-3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Instructions</label>
                </div>
                <textarea v-model="instructions" class="tw-form-control w-full" rows="3" placeholder="Payment instructions shown to users"></textarea>
                <p v-if="errors.instructions" class="text-red-500 text-xs mt-1">{{ errors.instructions[0] }}</p>
            </div>
        </div>

        <!-- Currency select — shown only for gateways that support multiple currencies -->
        <div class="tw-form-group w-full mt-3" v-if="currencyOptions.length > 0">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">
                        Default Currency
                        <span v-if="currencyOptions.length === 1" class="text-xs text-gray-400 font-normal ml-1">(fixed for this gateway)</span>
                    </label>
                </div>
                <select v-model="currency" class="tw-form-control w-full" :disabled="currencyOptions.length === 1">
                    <option value="">— Select currency —</option>
                    <option v-for="opt in currencyOptions" :key="opt.code" :value="opt.code">
                        {{ opt.code }} — {{ opt.label }}
                    </option>
                </select>
                <p v-if="errors.currency" class="text-red-500 text-xs mt-1">{{ errors.currency[0] }}</p>
            </div>
        </div>

        <div class="tw-form-group w-full mt-3">
            <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                    <label class="tw-form-label">Status<span class="text-red-500">*</span></label>
                </div>
                <div class="flex items-center">
                    <label class="mr-4">
                        <input type="radio" v-model="status" value="1" class="mr-1"> Active
                    </label>
                    <label>
                        <input type="radio" v-model="status" value="0" class="mr-1"> Inactive
                    </label>
                </div>
                <p v-if="errors.status" class="text-red-500 text-xs mt-1">{{ errors.status[0] }}</p>
            </div>
        </div>

        <div class="mt-5">
            <button @click="submitForm()" class="custom-green text-white py-2 px-6 rounded">Update</button>
            <a :href="url+'/admin/paymentgateways'" class="ml-3 text-gray-600">Cancel</a>
        </div>
    </div>
</template>

<script>
    const GATEWAY_CURRENCIES = {
        mpesa: [
            { code: 'KES', label: 'Kenyan Shilling' },
        ],
        gcash: [
            { code: 'PHP', label: 'Philippine Peso' },
        ],
        pix: [
            { code: 'BRL', label: 'Brazilian Real' },
        ],
        telebirr: [
            { code: 'ETB', label: 'Ethiopian Birr' },
        ],
        paystack: [
            { code: 'NGN', label: 'Nigerian Naira' },
            { code: 'GHS', label: 'Ghanaian Cedi' },
            { code: 'KES', label: 'Kenyan Shilling' },
            { code: 'ZAR', label: 'South African Rand' },
            { code: 'USD', label: 'US Dollar' },
            { code: 'EGP', label: 'Egyptian Pound' },
        ],
        flutterwave: [
            { code: 'NGN', label: 'Nigerian Naira' },
            { code: 'GHS', label: 'Ghanaian Cedi' },
            { code: 'KES', label: 'Kenyan Shilling' },
            { code: 'UGX', label: 'Ugandan Shilling' },
            { code: 'TZS', label: 'Tanzanian Shilling' },
            { code: 'ZAR', label: 'South African Rand' },
            { code: 'RWF', label: 'Rwandan Franc' },
            { code: 'MWK', label: 'Malawian Kwacha' },
            { code: 'ZMW', label: 'Zambian Kwacha' },
            { code: 'XAF', label: 'Central African CFA Franc' },
            { code: 'XOF', label: 'West African CFA Franc' },
            { code: 'USD', label: 'US Dollar' },
            { code: 'GBP', label: 'British Pound' },
            { code: 'EUR', label: 'Euro' },
        ],
        stripe: [
            { code: 'USD', label: 'US Dollar' },
            { code: 'EUR', label: 'Euro' },
            { code: 'GBP', label: 'British Pound' },
            { code: 'CAD', label: 'Canadian Dollar' },
            { code: 'AUD', label: 'Australian Dollar' },
            { code: 'SGD', label: 'Singapore Dollar' },
            { code: 'HKD', label: 'Hong Kong Dollar' },
            { code: 'JPY', label: 'Japanese Yen' },
            { code: 'CHF', label: 'Swiss Franc' },
            { code: 'MXN', label: 'Mexican Peso' },
            { code: 'BRL', label: 'Brazilian Real' },
            { code: 'INR', label: 'Indian Rupee' },
            { code: 'NZD', label: 'New Zealand Dollar' },
        ],
    };

    export default {
        props: ['url', 'gateway_id'],
        data() {
            return {
                gatewayname: '',
                displayname: '',
                instructions: '',
                currency: '',
                status: '1',
                errors: [],
                success: null,
            }
        },
        computed: {
            currencyOptions() {
                return GATEWAY_CURRENCIES[this.gatewayname] || [];
            },
        },
        watch: {
            // Auto-select when only one option available (e.g. mpesa → KES)
            currencyOptions(options) {
                if (options.length === 1 && !this.currency) {
                    this.currency = options[0].code;
                }
            },
        },
        methods: {
            getData() {
                axios.get(this.url + '/admin/paymentgateway/editList/' + this.gateway_id).then(response => {
                    const g = response.data.data;
                    this.gatewayname  = g.name;
                    this.displayname  = g.display_name;
                    this.instructions = g.instructions;
                    this.currency     = g.currency || '';
                    this.status       = String(g.status);

                    // Auto-set fixed-currency gateways
                    const options = GATEWAY_CURRENCIES[g.name] || [];
                    if (options.length === 1 && !this.currency) {
                        this.currency = options[0].code;
                    }
                });
            },
            submitForm() {
                this.errors = [];
                axios.post(this.url + '/admin/paymentgateway/update/' + this.gateway_id, {
                    gatewayname:  this.gatewayname,
                    displayname:  this.displayname,
                    instructions: this.instructions,
                    currency:     this.currency,
                    status:       this.status,
                }).then(response => {
                    this.success = response.data.success;
                    setTimeout(() => {
                        window.location.href = this.url + '/admin/paymentgateways';
                    }, 1000);
                }).catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                });
            },
        },
        created() {
            this.getData();
        }
    }
</script>
