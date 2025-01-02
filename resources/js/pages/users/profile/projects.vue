<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Users List</h4>
                <div class="col-md-1 pull-right" style="margin: -27px 0px 0px 0px;">
                    <newTask/>
                </div>
            </div>
            <div class="table-responsive add-project">
                <table class="table card-table table-vcenter text-nowrap">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created On</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><a class="text-inherit" href="#">Test Clinet</a></td>
                            <td>test_client</td>
                            <td>test_client@mail.com</td>
                            <td>05/15/2020</td>
                            <td><span class="status-icon bg-success"></span> Active</td>
                            <td class="text-center">
                                <a class="icon" href="javascript:void(0)"></a><a class="btn btn-primary btn-sm" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa fa-pencil"></i> Edit</a> <a class="icon" href="javascript:void(0)"></a>
                                <button class="btn btn-danger sweet-11" type="button" v-on:click="advanced_danger_alert"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import newTask from './newTask.vue';
export default {
    name: 'userProfile',
    components: {
        newTask,
    },
    methods: {
        advanced_success_alert:function(){
            this.$swal({
            text:'A wild Pikachu appeared! What do you want to do?',
            showCancelButton: true,
            showDenyButton: true,
            denyButtonText: 'run away!',
            confirmButtonText: 'Throw Pokéball!',
            confirmButtonColor: '#4466f2',
            cancelButtonText: 'Defeat',
            cancelButtonColor: '#4466f2',

            }).then((result)=>{
            if(result.value){
                this.$swal({
                title:'Gotcha!',
                text:'Pikachu was caught!',
                type:'success'
                });
            }
            else if(result.isDenied){
                this.$swal({
                text:'Go away Safely',
                })
            }
            else{
                this.$swal({
                text:'Pikachu fainted! You gained 500 XP!'
                });
            }
            });

        },
        advanced_danger_alert:function(){
            this.$swal({
            text:'Are you sure you want to do this?',
            showCancelButton: true,
            confirmButtonText: 'Ok',
            confirmButtonColor: '#4466f2',
            cancelButtonText: 'Cancel',
            cancelButtonColor: '#efefef',
            reverseButtons: true
            });
        },
    }

}
</script>
