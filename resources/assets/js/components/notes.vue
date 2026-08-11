<template>
<div>

  <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
   <label class="font-semibold text-lg text-gray-800 capitalize">Notes</label>
  <div class="flex py-4">
<div class="w-1/2">
        <ul > 
          
         <li v-for="(task, index) in tasks" class="task_item px-4 py-2 mb-2 bg-gray-200 border-b-1 flex" :key="task.id" >
          
        
            <div class="flex w-full" v-if="task.notes!=null">
            <div class="flex-1 flex flex-col">
             <div class="task_item_title"@click="getnotesDetails(task.id)">{{task.notes}}</div>
              <div><p class="date text-xs text-gray-500"@click="getnotesDetails(task.id)">{{task.created_at}}</p></div>
            </div>
            <div class="delete" style="cursor: pointer;" @click=" removeTask(task.id)">&times;</div>
           
          </div>
        
          
            
        </li>
      
     </ul>
      <p class="emptyList text-sm text-gray-700" v-show="tasks.length == 0">No notes added</p>
  </div>
   
  
     <div class="w-1/2 mx-4 ">
    <div class="form-group">
      <textarea rows = "5" cols = "50"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="notes" placeholder="Add a note">
            Enter your name
         </textarea>
         <span v-if="errors.notes"><p class="text-danger text-xs my-1">{{errors.notes[0]}}</p></span>

        <input c type="textarea" >
     
    </div>
   <div class="my-6">
  <div class="my-6">
  <a href="#" class="btn btn-primary submit-btn" @click="checkForm()">Submit</a>


</div>
</div>
   
    
    </div>
</div>

</div>

</div>
</template>

<script>
    //import Vue from 'vue'
    import { bus } from "../app";
    export default {
        props:['url','entity_id','entity_name','church_id'],
      data () {
        return {

             profile_tab:'',
             notes:'',
             newTask: "",
             tasks: [],
             createdby:'',
             updatedby:'',
             errors:[],
             success:null,
             id:'',
             entity:{
              'user':'App\\Models\\User',
              'event':'App\\Models\\Events',
             }            
        }
       },
      
       methods: {

         resetForm()
               {

                this.notes='';
                this.id='';
                
               }, 

 checkForm()
            {
              this.errors=[];
              this.success=null;    
              let formData=new FormData();
                   
              formData.append('notes',this.notes);          
               formData.append('entity_name',this.entity[this.entity_name]);          
              formData.append('entity_id',this.entity_id);          
              formData.append('church_id',this.church_id);          
              formData.append('id',this.id);          
            
        axios.post('/admin/notes',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
            this.success = response.data.message;
           this.resetForm();
            this.getallnotes();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },

      removeTask(id) {
            //this.tasks.splice(index, 1);
            var thisswal = this;
           swal({
             title: 'Are you sure',
             text: 'Are you sure to delete note??',
             icon: "warning",
             buttons: [
                 'No',
                 'Yes'
             ],
           dangerMode: true,
         }).then(function(isConfirm) {
             if (isConfirm) {

               axios.get(thisswal.url+ '/admin/notes/delete/'+ id).then(response => {
               thisswal.success = response.data.message;
             thisswal.getallnotes();
            });    
////post     
             }else {
                 swal("Cancelled");
             }
         });
        },

         getnotesDetails(id) {
            //this.tasks.splice(index, 1);
            axios.get(this.url+ '/admin/notes/edit/'+ id).then(response => {
              this.notes = response.data;
              this.id = id;
              

            });    
        },

      getallnotes(){
          
             let formData=new FormData();
                   
              formData.append('entity_name',this.entity[this.entity_name]);          
              formData.append('entity_id',this.entity_id);   
              axios.post(this.url+'/admin/getnotes',formData).then(response => { 
            this.tasks = response.data;
            // console.log(this.tasks);
            });      
        },

    
       
        },
       
        created(){
          //alert(this.entity_name);
          this.getallnotes();
    
          bus.$on("dataProfileTab", data => {
          if(data!='')
            {
              this.profile_tab=data;                   
          
            }
           });
        }

    }
</script>
<style>
.text-danger
{
  color:red;
}
</style>