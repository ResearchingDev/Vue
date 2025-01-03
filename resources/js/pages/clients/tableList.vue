<template>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">Client List</h4>
          <div class="col-md-1 pull-right" style="margin: -27px 0px 0px 0px;">
            <!-- Client modal component for Add/Edit -->
            <clientModals ref="clientModal" @updateCompleted="reloadDataTable"/>
          </div>
        </div>
        <div class="table-responsive add-project">
          <table class="table card-table table-vcenter text-nowrap" id="userTable">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Client Name</th>
                <th>Email</th>
                <th>Phone</th>
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
  import clientModals from './Modals.vue';
  import $ from 'jquery';
  import Swal from 'sweetalert2';
  import axios from 'axios';
  
  export default {
    name: 'clients',
    components: {
      clientModals,
    },
    mounted() {
      const vueInstance = this;
      window.vueInstance = vueInstance;
  
      // Initialize DataTable after the component is mounted
      $(document).ready(function () {
        $('#userTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: '/api/clients/list/', // Adjusted URL to match the route
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
            { data: 'client_name' },
            { data: 'client_email' },
            { data: 'phone_number' },
            { data: 'user_created_at' },
            { data: 'status' },
            {
              data: null,
              render: function (data, type, row) {
                return `
                  <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="window.vueInstance.openEditModal(${row.client_id})"><i class="fa fa-pencil"></i> Edit</a>
                  <button class="btn btn-danger sweet-11" type="button" data-id="${row.client_id}" onclick="deleteUser(${row.client_id})">
                    <i class="fa fa-trash"></i> Delete
                  </button>
                `;
              },
              orderable: false,
              searchable: false,
            }
          ]
        });
  
        window.deleteUser = (clientId) => {
          const vueInstance = window.vueInstance; // Reference to your Vue instance
          if (vueInstance) {
            vueInstance.deleteUser(clientId);
          }
        };
      });
    },
    methods: {
      // Function to open the edit modal and load the user data
      openEditModal(clientId) {
        axios.get(`/api/clients/${clientId}`)
          .then(response => {
            this.$refs.clientModal.openModal(response.data); // Pass the client data to the modal
          })
          .catch(error => console.error('Error fetching client data:', error));
      },
  
      // Function to delete the client
      deleteUser(clientId) {
        Swal.fire({
          title: 'Are you sure?',
          text: `You won't be able to revert this! Do you want to delete client with ID ${clientId}?`,
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
              .delete(`/api/clients/${clientId}`)
              .then(() => {
                Swal.fire(
                  'Deleted!',
                  'Client has been deleted successfully.',
                  'success'
                );
                $('#userTable').DataTable().ajax.reload();
              })
              .catch((error) => {
                Swal.fire(
                  'Error!',
                  'There was an error deleting the client. Please try again later.',
                  'error'
                );
                console.error('Error deleting client:', error);
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
  