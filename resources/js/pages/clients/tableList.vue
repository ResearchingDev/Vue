<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Client List</h4>
                <div class="col-md-1 pull-right" style="margin: -27px 0px 0px 0px;">
                    <userModals/>
                </div>
            </div>
            <div class="table-responsive add-project">
                <table class="table card-table table-vcenter text-nowrap" id="userTable">
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
                        <!-- Data will be populated here by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import userModals from './Modals.vue';
import $ from 'jquery';
import 'datatables.net-vue3';

export default {
    name: 'clients',
    components: {
        userModals,
    },
    mounted() {
        // Initialize DataTable after the component is mounted
        $(document).ready(function () {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/users/list', // Adjusted URL to match the route
                    type: 'GET',
                    data: function (d) {
                        // Pass necessary parameters for pagination, sorting, etc.
                        d.draw = d.draw;
                        d.start = d.start;
                        d.length = d.length;
                    }
                },
                columns: [
                    { data: 'id' },
                    { data: 'first_name' },
                    { data: 'user_type' },
                    { data: 'email' },
                    { data: 'created_at' },
                    { data: 'status' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return `
                                <a class="btn btn-primary btn-sm" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-pencil"></i> Edit</a>
                                <button class="btn btn-danger sweet-11" type="button" v-on:click="advanced_danger_alert"><i class="fa fa-trash"></i> Delete</button>
                            `;
                        },
                        orderable: false,
                        searchable: false,
                    }
                ]
            });
        });
    },
    methods: {
        advanced_danger_alert:function(){
            this.$swal({
                text: 'Are you sure you want to do this?',
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