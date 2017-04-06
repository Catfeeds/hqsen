<template>
  <div class="content-wrapper">
    <my-breadcrumb :data="basicInfo.breadcrumb"/>
    <div class="control-box">
      <el-input
        class="search-input"
        @keyup.enter="handSearch"
        v-if="type === 'account_info__hotel_list'"
        placeholder="跟进账号或酒店筛选"
        icon="search"
        v-model="searchInput"
        :on-icon-click="handSearch">
      </el-input>
       <el-button
        class="add-btn"
        v-if="addBtnType.indexOf(type) > -1"
        @click="addRow"
        type="primary">
        新增
      </el-button>
    </div>

    
<!--<el-popover
  ref="deleteBtn"
  placement="top"
  width="160"
  v-model="deleteLayer">
  <p>这是一段内容这是一段内容确定删除吗？</p>
  <div style="text-align: right; margin: 0">
    <el-button size="mini" type="text" @click="deleteLayer = false">取消</el-button>
    <el-button type="primary" size="mini" @click="deleteLayer = false">确定</el-button>
  </div>
</el-popover>

<el-button v-popover:deleteBtn>删除</el-button>-->

    <my-table
      @handleCurrentChange="handleCurrentChange($event)"
      :currentPage="currentPage"
      :total="total"
      :rowData="rowData" 
      :loading="loading"
      :columnData="basicInfo.columnData">
        <el-table-column
          prop="control"
          v-if="noControl.indexOf(type) < 0"
          label="操作">
            <template scope="scope">
              <!--查看详情-->
              <el-button 
                v-if="noDetailBtnType.indexOf(type) < 0" 
                @click="lookRow(scope)" 
                size="small">查看详情</el-button>
              <!--禁用/启用-->
              <el-button 
                v-if="disableBtnType.indexOf(type) > -1" 
                @click="disableRow(scope)" 
                size="small" 
                :plain="true" 
                type="warn">{{scope.row.disabled ? '启用' : '禁用'}}</el-button>
              <!--编辑-->
              <el-button 
                v-if="operateBtnType.indexOf(type) > -1" 
                icon="edit" 
                :disabled="scope.row.disabled"
                @click="editRow(scope)" 
                size="small"></el-button>
              <!--删除-->
              <el-button 
                v-if="operateBtnType.indexOf(type) > -1" 
                icon="delete" 
                :disabled="scope.row.disabled"
                @click="deleteRow(scope)" 
                size="small" 
                :plain="true" 
                type="danger"></el-button>
              <!--审批/重开-->
              <el-button 
                v-if="approveBtnType.indexOf(type) > -1" 
                @click="goApprove(scope)" 
                size="small" 
                :plain="true" 
                type="warn">审批</el-button>
              <!--已完成/完成打款-->
              <span 
                v-if="moneyBntType.indexOf(type) > -1">已完成</span>
            </template>
        </el-table-column>
    </my-table>
  </div>
</template>

<style>
  .control-box {
    margin: 20px 0;
    &:after {
      content: "";
      display: block;
      clear: both;
    }
    & .search-input {
      width: 250px;
    }
    & .add-btn {
      margin-right: 10px;
      float: right;
    }
  }
</style>

<script>
  import { TableColumn, Button, Input, MessageBox, Message } from 'element-ui'
  import MyTable from '../../components/MyTable'
  import MyBreadcrumb from '../../components/MyBreadcrumb'
  import config from './config'
  import Fetch from '../../Fetch'
  import { configData } from '../../commonData'
  export default {
    components: {
      MyTable,
      MyBreadcrumb,
      ElTableColumn: TableColumn,
      ElButton: Button,
      ElInput: Input
    },
    created () {
      this.setBasicInfo()
    },
    data () {
      return {
        type: '',           // 列表类型
        searchInput: '',    // 输入框
        hideOperate: false,
        noControl: ['account_info__register_list'],
        noDetailBtnType: ['hotel_info__hotel_list', 'hotel_info__area_list', 'account_info__hotel_list', 'account_info__inner_list'],
        moneyBntType: ['remittance_info__kezi_contract', 'remittance_info__dajian_contract'],
        approveBtnType: ['finance_info__kezi_contract', 'finance_info__dajian_contract', 'manager_info__kezi_contract', 'manager_info__dajian_contract'],
        operateBtnType: ['account_info__hotel_list', 'account_info__inner_list', 'hotel_info__area_list', 'hotel_info__hotel_list'],
        disableBtnType: ['account_info__hotel_list', 'account_info__inner_list'],
        addBtnType: ['account_info__hotel_list', 'account_info__inner_list', 'hotel_info__area_list', 'hotel_info__hotel_list'],
        rowData: [],
        basicInfo: {
          uniqueKey: '',
          columnData: [],
          breadcrumb: []
        },
        loading: true,
        total: 55,     // 总页数
        currentPage: 1 // 当前页
      }
    },
    watch: {
      '$route': 'setBasicInfo'
    },
    methods: {
      // 设置基础信息
      setBasicInfo () {
        const type = this.$route.params.type
        // const { breadcrumb, columnData, uniqueKey } = config[type]
        this.basicInfo = config[type]
        this.$emit('setActiveIndex', type)
        this.type = type
        // this.breadcrumb = breadcrumb
        // this.columnData = columnData
        this.handleCurrentChange(1)
      },
      // 分页跳转
      handleCurrentChange (current) {
        console.log('currentpage', current)
        Fetch(this.basicInfo.listUrlKey, { page: current }, 'post', true).then(response => {
          const data = response.data.list
          if (this.type === 'order_info__kezi_list') {
            data.map(item => {
              configData().order_type.map(sub => {
                if (item.order_type === sub.value) {
                  item.order_type_name = sub.label
                }
              })
            })
          }
          this.rowData = data
          this.currentPage = current
          this.total = response.data.count
        })
      },
      // 搜索
      handSearch () {
        const { searchInput } = this
        if (searchInput) {
          console.log(searchInput)
        } else {
          Message({
            message: '查询内容不能为空',
            type: 'warn'
          })
        }
      },
      // --查看详情--
      lookRow (data) {
        // const rowData = data.row
        this.$router.push({
          path: '/detail',
          name: 'Detail',
          param: { type: this.type },
          query: { id: data.row[this.basicInfo.uniqueKey] }
        })
      },
      // --禁用--
      disableRow (data) {
        MessageBox({
          message: '您确定要禁用该条数据？',
          type: 'warning',
          showCancelButton: true,
          callback: (action, instance) => {
            if (action === 'confirm') {
              // do something
              const id = data.row.id
              this.rowData.map(item => {
                if (item.id === id) {
                  item.disabled = !item.disabled
                }
              })
            }
          }
        })
      },
      // --编辑--
      editRow (data) {
        this.$router.push({
          path: '/add',
          name: 'Add',
          params: { type: this.type },
          query: { id: data.row[this.basicInfo.uniqueKey] }
        })
      },
      // --删除--
      deleteRow (data) {
        MessageBox({
          message: '您确定要删除该条数据？',
          type: 'warning',
          showCancelButton: true,
          callback: (action, instance) => {
            if (action === 'confirm') {
              // do something
              const { deleteUrlKey, uniqueKey } = this.basicInfo
              Fetch(deleteUrlKey, { id: data.row[uniqueKey] }).then(response => {
                Message({
                  message: '删除成功',
                  type: 'success'
                })
                this.handleCurrentChange(this.currentPage)
              })
            }
          }
        })
      },
      // --添加--
      addRow () {
        this.$router.push({
          path: '/add',
          name: 'Add',
          params: { type: this.type }
        })
      },
      // 审批
      goApprove (scope) {
        // let param = 'custom,tBuild,mBuild,dBuild'
        let control = this.type.indexOf('man') > -1 ? 'man' : 'caiwu'
        this.$router.push({
          path: '/approve',
          name: 'Approve',
          params: { type: 'custom' },
          query: { control }
        })
      }
    }
  }
</script>
