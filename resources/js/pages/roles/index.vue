<template>
    <Breadcrumbs title="User Roles" main="Roles" />
    <div class="card">
        <div class="col-md-12">
            <router-link class="link btn btn-primary m-4 pull-right" to="/client/roles/add_role"> Add User Roles</router-link>
        </div>
        <div class="container-fluid">
            <div class="user-profile">
                <div class="row">
                    <table class="table card-table table-vcenter text-nowrap" id="userRolesTable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Role Name</th>
                                <th>Access</th>
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
    </div>
</template>

<script>
    import $ from 'jquery';
    import Swal from 'sweetalert2';
    import axios from 'axios';

    export default {
        name: 'userProfile',
        components: {
        },
        mounted() {
            const vueInstance = this;
            window.vueInstance = vueInstance;

            // Initialize DataTable after the component is mounted
            $(document).ready(function () {
                const user = localStorage.getItem('User');
                const parsedUser = JSON.parse(user);
                const clientId = parsedUser.client_id;
                $('#userRolesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/api/client/'+clientId, // Adjusted URL to match the route
                        type: 'GET',
                        data: function (d) {
                        // Pass necessary parameters for pagination, sorting, etc.
                        d.draw = d.draw;
                        d.start = d.start;
                        d.length = d.length;
                        }
                    },
                    columns: [
                        {
                            data: null,
                            render: function (data, type, row, meta) {
                                // Calculate serial number based on row index (meta.row) and page number
                                return meta.row + meta.settings._iDisplayStart + 1; // +1 for 1-based index
                            },
                        },
                        { data: 'role_name' },
                        {
                            data: null, // Custom rendering for web and mobile access
                            render: function (data, type, row) {
                                let access = [];
                                if (row.web_access === 'Yes') access.push('Web Access');
                                if (row.mobile_access === 'Yes') access.push('Mobile Access');
                                return access.length > 0 ? access.join(', ') : '-';
                            }
                        },
                        { data: 'status' },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return `
                                    <button class="btn btn-primary btn-sm" onclick="window.vueInstance.editRole(${row.id})"><i class="fa fa-pencil"></i> Edit</button>
                                    <button class="btn btn-danger sweet-11" type="button" onclick="window.vueInstance.deleteRole(${row.id})"><i class="fa fa-trash"></i> Delete </button>
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
            // Function to delete the User Role
            editRole(roleId) {
                window.location.href = '/client/roles/edit/' + roleId;
            },
            // Function to delete the User Role
            deleteRole(roleId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You won't be able to revert this! Do you want to delete User Role?`,
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
                            .delete(`/api/client/roles/delete/${roleId}`)
                            .then((response) => {
                                Swal.fire(
                                    'Deleted!',
                                    response.data.message || 'User Role has been deleted successfully.',
                                    'success'
                                );
                                $('#userRolesTable').DataTable().ajax.reload();
                            })
                            .catch((error) => {
                                let errorMessage = error.response?.data?.message || 'There was an error deleting the User Role. Please try again later.';
                                Swal.fire('Error!', errorMessage, 'error');
                                console.error('Error deleting User Role:', error);
                            });
                    }
                });
            },
        }
    }
</script>
