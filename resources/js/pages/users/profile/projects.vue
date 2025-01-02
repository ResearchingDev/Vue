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
                                <a class="btn btn-danger btn-sm" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete</a>
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

}
</script>
