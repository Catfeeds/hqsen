<template>
  <div>
    <el-table
      :data="rowData"
      border>
      <el-table-column
        v-for="(item,index) in columnData"
        :key="index"
        :prop="item.name"
        :label="item.label"
        :width="item.width">
      </el-table-column>
      <slot></slot>
    </el-table>
    <el-pagination
      class="my-pagination"
      @current-change="$emit('handleCurrentChange',$event)"
      :current-page="currentPage"
      :page-size="20"
      :total="total">
    </el-pagination>
  </div>
</template>

<style>
  .my-pagination {
    text-align: right;
    margin-top: 10px;
  }
</style>

<script>
  import { Table, TableColumn, Pagination } from 'element-ui'
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
    }
  }
</script>
