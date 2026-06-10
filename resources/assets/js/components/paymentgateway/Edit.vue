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
    export default {
        props: ['url', 'gateway_id'],
        data() {
            return {
                gatewayname: '',
                displayname: '',
                instructions: '',
                status: '1',
                errors: [],
                success: null,
            }
        },
        methods: {
            getData() {
                axios.get(this.url + '/admin/paymentgateway/editList/' + this.gateway_id).then(response => {
                    const g = response.data.data;
                    this.gatewayname  = g.name;
                    this.displayname  = g.display_name;
                    this.instructions = g.instructions;
                    this.status       = String(g.status);
                });
            },
            submitForm() {
                this.errors = [];
                axios.post(this.url + '/admin/paymentgateway/update/' + this.gateway_id, {
                    gatewayname:  this.gatewayname,
                    displayname:  this.displayname,
                    instructions: this.instructions,
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
