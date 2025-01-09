<template>
    <div class="table-container">
      <table ref="table" :id="tableId" :class="tableClass">
        <thead>
          <tr>
            <th>S.No</th>
            <!-- Loop through column headers passed as a prop -->
            <th v-for="(header, index) in columns" :key="index">{{ header.label }}</th>
          </tr>
        </thead>
        <tbody>
          <!-- Loop through each row of data -->
          <tr v-for="(row, rowIndex) in tableData" :key="getRowKey(row)">
            <!-- Loop through each column for the current row -->
            <td>{{ rowIndex + 1 }}</td>
            <td
              v-for="(column, colIndex) in columns"
              :key="colIndex"
              :class="column.cellClass">
              <template v-if="column.customRender">
                <span v-html="column.customRender(row)"></span>
              </template>
              <template v-else>
                <!-- Default cell rendering -->
                {{ row[column.field] }}
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    name: 'TableComponent',
    props: {
      columns: {
        type: Array,
        required: true
      },
      tableData: {
        type: Array,
        required: true
      },
      tableClass: {
        type: String,
        default: 'table' // Default class for table
      },
      tableId: {
      type: String,
      default: '' // Default to an empty string if no id is provided
    }
    },
    methods: {
      // Get unique row key if available or use the index as fallback
      getRowKey(row) {
        return row.id || row._id || row.index; // Ensure a unique identifier if possible
      }
    }
  };
  </script>
  
  <style scoped>
  .table-container {
    max-width: 100%;
    overflow-x: auto;
  }
  
  .table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .table th,
  .table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd;
  }
  
  .table th {
    background-color: #f4f4f4;
  }
  
  .table td {
    word-break: break-word;
  }
  </style>
  