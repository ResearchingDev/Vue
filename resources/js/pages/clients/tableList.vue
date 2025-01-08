<template>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">Client List</h4>
          <div class="col-md-1 pull-right" style="margin: -27px 0px 0px 0px;">
            <!-- Client modal component for Add/Edit -->
            <clientModals ref="clientModal" @updateCompleted="handleUpdateCompleted"/>
          </div>
          <span id="message"  class="text-success d-block"></span>
        </div>
        <div class="card-body">
          <div class="table-responsive add-project">
            <table class="table card-table table-center text-nowrap" id="userTable">
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
    </div>
  </template>
  
  <script>
  import clientModals from './Modals.vue';
  import $ from 'jquery';
  import Swal from 'sweetalert2';
  import axios from 'axios';
import { toast } from 'vue3-toastify';
  
  export default {
    name: 'clients',
    components: {
      clientModals,
    },
    data() {
      return {
        message: '', 
      };
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
            url: '/api/clients/list/',
            type: 'GET',
            data: function (d) {
              // Pass additional parameters for sorting and searching
              d.draw = d.draw;
              d.start = d.start;
              d.length = d.length;
              d.search = d.search;
              d.order = d.order;
            },
          },
          columns: [
            {
              data: null,
              render: function (data, type, row, meta) {
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
                  <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="window.vueInstance.openEditModal(${row.client_id})"><i class="fa fa-pencil"></i></a>
                  <button class="btn btn-danger btn-sm sweet-11" type="button" data-id="${row.client_id}" onclick="deleteUser(${row.client_id})">
                    <i class="fa fa-trash"></i>
                  </button>
                `;
              },
              orderable: false,
              searchable: false,
            },
          ],
          order: [[1, 'asc']], // Default sort by the second column (Client Name)
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
          text: `You won't be able to revert this! Do you want to delete client?`,
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
      handleUpdateCompleted() {
         this.reloadDataTable();
      },
      reloadDataTable() {
            $('#userTable').DataTable().ajax.reload();
        },
    }
  }
  </script>
  