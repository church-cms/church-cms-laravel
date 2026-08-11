<template>
    <div>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="tw-form-group">

            <div class="mb-2">
                <label class="tw-form-label">To</label>
            </div>

            <multiselect v-model="to" id="ajax" label="fullname" track-by="fullname" placeholder="Type to search" open-direction="bottom" :options="users" :custom-label="customLabel" :show-labels="true" :multiple="true" :searchable="true" :loading="isLoading" :internal-search="true" :clear-on-select="false" :close-on-select="false" :options-limit="200" :limit="5" :limit-text="limitText" :max-height="200" :show-no-results="true" :hide-selected="true" @search-change="asyncFind">

                <template slot="tag" slot-scope="{ option, remove }">
                    <span class="custom__tag">
                        <span>{{ (option.fullname) }}</span>
                        <span class="custom__remove" @click="remove(option)">❌</span>
                    </span>
                </template>

                <template slot="clear" slot-scope="props">
                    <div class="multiselect__clear" v-if="to.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                </template>
        
                <template slot="option" slot-scope="props">
                    <div class="option__desc">
                        <span class="option__name">{{ props.option.fullname }}</span>
                    </div>
                    <div class="option__desc">
                        <span class="option__small">{{ props.option.mobile_no }}</span>
                    </div>
                </template>

                <span slot="noResult">Oops! No users found.</span>
        
            </multiselect>
            <span v-if="errors.to" class="text-red-500 text-xs font-semibold">{{errors.to[0]}}</span>

            <div class="my-5 w-full">
                <div class="w-full">
                    <label for="message" class="tw-form-label">Message</label>
                </div>
                <div class="w-full my-2">
                    <select class="tw-form-control w-full" id="message" v-model="message" v-on:change="enableDiv()" name="message">
                        <option v-for="message in messages" v-bind:value="message.template">{{message.template}}</option>
                        <option value="type">Type</option>
                    </select>
                </div>
                <div class="w-full my-2 hidden" id="others">
                    <textarea type="text" name="message" v-model="message" class="tw-form-control w-full" rows="10"></textarea>
                </div>
                <span v-if="errors.message" class="text-red-500 text-xs font-semibold">{{errors.message[0]}}</span>
            </div>
      
            <div class="my-6">
                <a href="#" class="btn btn-primary submit-btn" @click="sendMessage()">Submit</a>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    // register globally
    Vue.component('multiselect', Multiselect)
    export default {
        props:['url'],
        components: {
            Multiselect
        },
        data () {
            return {
                to: [],
                users: [],
                messages: [],
                message:'',
                isLoading: false,
                errors:[],
                success:null,
            }
        },

        methods: 
        {
            getMember(query)
            {
                axios.get('/admin/dashboard/anniversaryUser?'+query).then(response => {
                    this.user = response.data;
                    this.setMember(query); 
                });
            },

            setMember(query)
            {
                if(Object.keys(this.user).length>0)
                {
                    this.users = this.user.anniversarylist;
                    this.messages = this.user.templatelist;
                    this.isLoading = false;
                }
            },

            limitText (count) 
            {
                return `and ${count} other users`
            },

            customLabel ({ fullname, mobile_no }) 
            {
                return `${fullname} – ${mobile_no}`
            },

            clearAll () 
            {
                this.to = []
            },

            asyncFind (query) 
            {
                //console.log(query);
                this.isLoading = true
                this.getMember(query);
            },

            resetForm()
            {
                this.to='';
                this.message='';      
            }, 

            sendMessage()
            {
                this.errors=[];
                this.success=null; 
                axios.post('/admin/dashboard/anniversary',{
                    to: this.to,
                    message:this.message,
                }).then(response => {             
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            enableDiv()
            {
                //alert(this.message);
                if(this.message == 'type')
                {
                    this.message = '';
                    if($('#others').hasClass('hidden'))
                    {
                        $('#others').removeClass('hidden').addClass('block');
                    }
                }
                else
                { 
                    if($('#others').hasClass('block'))
                    {
                        $('#others').removeClass('block').addClass('hidden');
                    }
                }
            },
        },
        created()
        {
            this.getMember();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>