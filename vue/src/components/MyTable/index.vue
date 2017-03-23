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
          <!--<template scope="scope">
            <el-button
              @click.native.prevent="deleteRow(scope.$index, tableData4)"
              type="text"
              size="small">
              移除
            </el-button>
          </template>-->
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
      columnData: Array
    },
    data () {
      return {}
    },
    methods: {
      hhh (data) {
        console.log(data)
      }
    }
  }
</script>
