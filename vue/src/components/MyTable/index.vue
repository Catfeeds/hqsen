<template>
  <div class="table-box">
    <el-table
      :data="rowData"
      v-loading.body="loading"
      :row-class-name="disableRowClassName"
      border>
      <el-table-column
        v-for="(item,index) in columnData"
        :key="index"
        :prop="item.name"
        :label="item.label"
        :width="item.width"/>
      <slot></slot>
    </el-table>
    <el-pagination
      class="my-pagination"
      @current-change="$emit('handleCurrentChange',$event)"
      :current-page="currentPage"
      :page-size="10"
      :total="total"/>
  </div>
</template>

<style>
  .table-box {
    position: relative;
  }
  .my-pagination {
    text-align: right;
    margin-top: 10px;
  }
  .disable-row {
    background: #f5f3f3 !important;
    color: #ccc;
  }
</style>

<script>
  import Vue from 'vue'
  import { Table, TableColumn, Pagination, Loading } from 'element-ui'

  Vue.use(Loading)

  export default {
    components: {
      ElTable: Table,
      ElTableColumn: TableColumn,
      ElPagination: Pagination
    },
    props: {
      currentPage: Number,
      total: Number,
      rowData: Array,
      columnData: Array,
      loading: Boolean
    },
    methods: {
      disableRowClassName (row, index) {
        if (row.disabled) {
          return 'disable-row'
        }
        return ''
      }
    }
  }
</script>
