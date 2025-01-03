<template>
    <editUser ref="editUser" @updateCompleted="reloadDataTable" />
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
                            <th>Usertype</th>
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
import editUser from './editUser.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: 'clients',
    components: {
        newTask,
        editUser,
    },
    mounted() {
        const vueInstance = this; 
        window.vueInstance = vueInstance; 
        $(document).ready(() => {
            const table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/users/list',
                    type: 'POST',
                    data: function (d) {
                        d.draw = d.draw;
                        d.start = d.start;
                        d.length = d.length;
                        d.search = d.search.value;
                        d.order = d.order;
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
                        render: (data, type, row) => {
                            return `
                                <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="openEditModal(${row.id})"><i class="fa fa-pencil"></i> Edit</a>
                                <button class="btn btn-danger sweet-11" type="button" data-id="${row.id}" onclick="deleteUser(${row.id})">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            `;
                        },
                        orderable: false,
                        searchable: false,
                    }
                ],
                order: [[0, 'asc']],
            });
            // Fix for jQuery event handler calling openEditModal
            window.openEditModal = (userId) => {
                this.openEditModal(userId); // Call the Vue method directly
            };
            window.deleteUser = (userId) => {
                const vueInstance = window.vueInstance; // Reference to your Vue instance
                if (vueInstance) {
                    vueInstance.deleteUser(userId);
                }
            };
        });
    },
    methods: {
        openEditModal(userId) {
            axios.get(`/api/admin/users/edit/${userId}`)
                .then(response => {
                    this.$refs.editUser.openModal(response.data); // Pass the user data to the editUser component
                })
                .catch(error => console.error('Error fetching user data:', error));
        },
        deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You won't be able to revert this! Do you want to delete user with ID ${userId}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#d33',
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#3085d6',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .delete(`/api/admin/users/delete/${userId}`)
                        .then(() => {
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted successfully.',
                                'success'
                            );
                            $('#userTable').DataTable().ajax.reload();
                        })
                        .catch((error) => {
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the user. Please try again later.',
                                'error'
                            );
                            console.error('Error deleting user:', error);
                        });
                }
            });
        },
        reloadDataTable() {
            $('#userTable').DataTable().ajax.reload();
        },
    }
}
</script>
