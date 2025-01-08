<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Users List</h4>
                <div class="col-md-1 pull-right" style="margin: -27px 0px 0px 0px;">
                    <Modals ref="UserModal" @updateCompleted="handleUpdateCompleted" />

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
import Modals from './Modals.vue';
import $ from 'jquery';
import 'datatables.net-vue3';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    name: 'userProfile',
    components: {
        Modals
    },
    mounted() {
        const vueInstance = this;
        window.vueInstance = vueInstance;
        $(document).ready(() => {
            const token = localStorage.getItem('token'); // Get the token from localStorage
            const table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/client/users/list',
                    type: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,  // Include the token in the request headers
                    },
                    data: function (d) {
                        d.draw = d.draw;
                        d.start = d.start;
                        d.length = d.length;
                        d.search = d.search.value;
                        d.order = d.order;
                    },
                    error: function (xhr, error, thrown) {
                        // You can also handle specific error codes here
                        if (xhr.status === 401) {
                            // Handle Unauthorized (e.g., user session expired)
                            // Redirect to login page or perform other actions
                            window.location.href = '/login';
                        } else if (xhr.status === 500) {
                            // Handle server errors
                            alert('Internal Server Error. Please try again later.');
                        }
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
                              <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="window.vueInstance.openEditModal(${row.id})"><i class="fa fa-pencil"></i> Edit</a>
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
            window.deleteUser = (userId) => {
                const vueInstance = window.vueInstance; // Reference to your Vue instance
                if (vueInstance) {
                    vueInstance.deleteUser(userId);
                }
            };
        });
    },
    methods: {
        // Function to open the edit modal and load the user data
        openEditModal(userId) {
            this.$axios.get(`/client/users/edit/${userId}`)
              .then(response => {
                this.$refs.UserModal.openModal(response);  // Open modal with fetched data
              })
              .catch(error => {
                console.error('Error fetching User data:', error);
              });
        },
        handleUpdateCompleted() {
            this.reloadDataTable();
        },
        deleteUser(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You won't be able to revert this! Do you want to delete user?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                confirmButtonColor: '#d33',
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#3085d6',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$axios.delete(`/client/users/delete/${userId}`)
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
