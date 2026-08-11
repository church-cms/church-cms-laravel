<template>
    <div>
        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="from_email" class="tw-form-label">Template<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <select class="tw-form-control w-full" v-model="select_template" @change="getTemplate()">
                            <option value="" disabled>Select Template</option>
                            <option :value="list.template_slug" v-for='list in templatelists'>{{ list.template_name }}</option>   
                        </select>
                        <span v-if="errors.select_template" class="text-red-500 text-xs font-semibold">{{ errors.select_template[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
  
        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="value" class="tw-form-label">Content<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <vue-simplemde v-model="value" ref="markdownEditor" id="visual-editor" dusk="content"/>
                        <div class="help-text help-text mt-2"></div>
                        <span v-if="errors.value" class="text-red-500 text-xs font-semibold">{{ errors.value[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueSimplemde from 'vue-simplemde';
    export default {
        props: ['resourceName', 'resourceId', 'field'],
        components: {
            VueSimplemde
        }, 

        data () {
            return {
                template:[],
                select_template:this.field.select_template,
                templatelists:[],
                value:'',
                success:null,
                errors:[],
            }
        },

        methods: 
        {
            /*
             * Set the initial, internal value for the field.
            */
            setInitialValue() 
            {
                this.value = this.field.value || '';
                if(this.value!='')
                {
                    this.setValue();
                } 
            },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
         getMailablesList(){
          axios.get('/admin/maileclipse/templates/getTemplatesList').then(response => {
            this.templatelists=response.data;                    
                 });
     },
      getTemplate(){

           axios.get('/admin/maileclipse/templates/gettemplate/'+this.select_template).then(response => {
                   this.template=response.data;
                  this.value=response.data['template'];
                    this.setValue();
                 });
     },
     setValue(){
        this.simplemde.value(this.value);
        this.simplemde.togglePreview(); 
        this.simplemde.togglePreview();
      
     
     }
    },
   computed: {
      simplemde () {
        return this.$refs.markdownEditor.simplemde
      }
    },
      watch: {
    // Setup a watch to track changes,
    // and only update when changes are made
    value(newVal) {
      if (newVal != this.simplemde.value()) {
        this.simplemde.value(newVal);
    
      }
    }
  },
    mounted(){
        let preview=this.simplemde.togglePreview();
      this.getMailablesList();
      if(this.value=='')
      {
         this.getTemplate();
      }

          

      //   console.log(this.simplemde)
    
      // 'change' envent has bound, via @input attache an event listener
      // You can attache events in this [list](https://codemirror.net/doc/manual.html#events) yourself if necessary
      this.simplemde.codemirror.on('beforeChange', (instance, changeObj) => {
        // do some things

      })
       this.simplemde.codemirror.on('change', (instance, changeObj) => {
        // do some things

          this.value =this.simplemde.value();

      })
      // remove SimpleMDE, when component destroy will invoke
    //  this.simplemde = null
      // some useful methods
     // this.$refs.markdownEditor.initialize() // init
      //this.simplemde.toTextArea()
      //this.simplemde.isPreviewActive() // returns boolean
     // this.simplemde.isSideBySideActive() // returns boolean
     // this.simplemde.isFullscreenActive() // returns boolean
     // this.simplemde.clearAutosavedValue() // no returned value
     // this.simplemde.markdown(this.value) // returns parsed html
     // this.simplemde.codemirror.refresh() // refresh codemirror

            var self=this;
        this.simplemde.options.previewRender = function(plainText,preview) {
               // console.log('preview');
               // console.log(preview);
            // Note: "this" refers to the options object
              // / console.log(plainText);
              //preview.innerHTML ='';
                axios.post('/admin/maileclipse/templates/getpreviewMarkdownViewContent',{
                       markdown: plainText, 
                       name:self.select_template,
                }).then(response => {
                    preview.innerHTML = response.data;
                   // console.log(response.data);
                  /*  #visual-editor > div.CodeMirror.cm-s-paper.CodeMirror-wrap > div.editor-preview.markdown-body.editor-preview-active*/
                 // document.querySelector('#visual-editor > div.CodeMirror.cm-s-paper.CodeMirror-wrap > div.editor-preview.markdown-body.editor-preview-active').innerHTML=response.data;
                   // document.getElementsByClassName('editor-preview-active').innerHTML=response.data;
                    //return response.data;
                       //return this.parent.markdown(response.data);
                       //document.getElementById('visual-editor').innerHTML=response.data;
                    
                 });
                      return '';
           // return this.parent.markdown(plainText);
        };
    

    }
}
</script>