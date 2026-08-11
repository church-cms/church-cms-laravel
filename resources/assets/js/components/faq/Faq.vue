<template>
    <VueFaqAccordion :items="myItems"/>
</template>
 
<script>
    import VueFaqAccordion from 'vue-faq-accordion'
    export default {
        components: {
            VueFaqAccordion
        },
        data () {
            return {
                myItems: []
            }
        },

        methods:
        {
            getData()
            {
                axios.get("/faq/get").then(response => {
                    this.myItems = response.data;
                });
            },
        },

        created () 
        {
            this.getData();
        }
    }
</script>

<style>
    nav.faq__nav 
    {
        border: 0 !important;
        display: block !important;
    }

    .faq__nav-item 
    {
        height: 45px !important;
        margin-bottom: 20px;
        text-align: unset !important;
        justify-content:start !important;
        font-size: 12px !important;
        margin: 10px auto !important;
        padding: 16px 20px !important;
        border-right: unset !important
    }

    .faq__nav-item:last-child 
    {
       border-right: none! important;
    }

    .accordion 
    {
        border: 0 !important;
        width: 100%;
        margin-top: 0px ! important;
        margin-left: 20px !important;
        border-left: 1px solid #ccc !important;
        border-radius: unset !important;
    }

    .accordion__item 
    {
        background-color: #fff;
        border-bottom: 0 !important;
        color:#1e0a5a;
        font-weight: 600;
    }

    .accordion__title
    {
        color:#1e0a5a! important;
        padding:12px 12px !important;
    }

    .accordion__value
    {
        padding: 12px! important;
        text-align: left;
        /*  background-color:#ebeff3;*/
        font-weight: 300;
        line-height: 2em;
        text-align: justify;
        padding-top: 5px !important;
    }

    .faq-wrapper 
    {
        display: flex;
        max-width: 100% !important;
    }

    .faq__nav-item_active
    {
        color: #3492e2 !important;
       /* background: #1B65D3 !important;
        border-radius: 3px !important;*/
    }

    /*@media(max-width:767px) {
      .faq-wrapper {
        display: block;
      }
    }*/

    @media(max-width: 540px) 
    {
        .accordion 
        {
            border-left: unset !important;
        }
    }

    .accordion__item 
    {
        margin-left: 25px !important;
        margin-right: 25px !important;
        border-bottom: 1px solid #ccc !important;
    }

    .faq__nav-item 
    {
        font-size: 15px !important;
    }

    .faq__nav-item:hover 
    {
        color: #3492e2 !important;
    }

    .faq__nav 
    {
        width: 20%;
    }

    .accordion__title-text 
    {
        color: #000;
        font-weight: 400;
        font-size: 18px;
    }
</style>