<template>
 <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==3?'block' :'hidden']">
  <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
  <div class="flex py-4">
     <p class="emptyList" v-show="tasks.length == 0">No notes added</p>
<!--     <div class="clearButtons" v-if="tasks.length > 0">
        <button class="btn mb1 black bg-silver" :disabled="completedTasks == 0" @click="displayModalClearCompleted()"><i class="fa fa-check"></i>&nbsp;Clear Completed</button>
        <button class="btn btn-primary mr-8 w-32" @click="displayModalClearAll()"><i class="fa fa-trash"></i>&nbsp;Clear All</button>
    </div> -->

<div class="w-1/2 m-4">
 <label class="flex-1 py-4">Notes</label>

        <ul > 
          
         <li v-for="(task, index) in tasks" class="task_item px-4 py-2 mb-2 bg-gray-200 border-b-1 flex" :key="task.id" >
          
        
            <div class="flex w-full" v-if="task.notes!=null">
            <div class="flex-1 flex flex-col">
              <div class="task_item_title">{{task.notes}}</div>
              <div><p class="date text-xs text-gray-500">{{task.created_at |formatDate}}</p></div>
            </div>
            <div class="delete" style="cursor: pointer;" @click="removeTask(task.id)">&times;</div>
          </div>
        
          
            
        </li>
       <!--  <todo v-for="(task, index) in incompleteTasks" :task="task" :key="index" @complete="completeTask(task)" @remove="removeTask(index)"></todo> -->
     </ul>
  </div>
   
  
     <div class="w-1/2 mx-4 ">
    <div class="form-group">
      <textarea rows = "5" cols = "50" id="addnotes"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="notes" placeholder="Add a note" @keyup.enter="addTask()">
            Enter your name
         </textarea>
          <span v-if="errors.notes"><small class="text-red">{{errors.notes[0]}}</small></span>

        <input c type="textarea" >
       <!--  <button class="button is-primary" @click="addTask()" :disabled="newTask.length < 3"><i class="fa fa-plus"></i>&nbsp;Add</button> -->
    </div>
   <div class="my-6">
  <div class="my-6">
  <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="checkForm()">Submit</a>


</div>
</div>
   
    
    </div>
</div>
     <modal v-if="showModalClearCompleted" @close="showModalClearCompleted=false">
        <template slot="header">
            Warning
        </template>
        You're about to delete all the completed ToDo's in the list
        <template slot="footer">
            <button class="button is-danger" @click="clearCompleted(),showModalClearCompleted=false">Confirm</button>
            <button class="button" @click="showModalClearCompleted=false">Cancel</button>
        </template>
    </modal>
    <modal v-if="showModalClearAll" @close="showModalClearAll=false">
        <template slot="header">
            Warning
        </template>
        You're about to delete all the ToDo's in the list
        <template slot="footer">
            <button class="button is-danger" @click="clearList(),showModalClearAll=false">Confirm</button>
            <button class="button" @click="showModalClearAll=false">Cancel</button>
        </template>
    </modal>
   <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
            <p class="modal-card-title"><slot name="header"></slot></p>
            </header>
        <section class="modal-card-body">
        <slot></slot>
        </section>
        <footer class="modal-card-foot">
        <slot name="footer"></slot>
        </footer>
        </div>
    </div>
    
</div>


</template>

<script>
    //import Vue from 'vue'
    import { bus } from "../../../app";
    export default {
        props:['url','id','entity'],
      data () {
        return {

             profile_tab:'',
             notes:'',
             newTask: "",
             tasks: [],
             showModalClearAll: false,
             showModalClearCompleted: false,
             showModal: false,
             createdby:'',
             updatedby:'',
             errors:[],
             success:null,
         

            
        }
       },
      
       methods: {

         resetForm()
               {

                this.notes='';
                
               }, 

 checkForm()
            {
              this.errors=[];
              this.success=null;    
              let formData=new FormData();
                   
              formData.append('notes',this.notes);          
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

      getallnotes(){
            //alert(this.emp_id);
            axios.get(this.url+'/admin/notes/' + this.id).then(response => {
             
            this.tasks = response.data;
            //console.log(this.tasks);
            });      
        },

    
       
        },
       
        created(){
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
