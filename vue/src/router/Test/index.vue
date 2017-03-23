<template>
  <main>
    <my-menu mode="horizontal" :data="headData" theme="dark" :activeIndex="headIndex" @handleSelect="handleSelect($event)"></my-menu>
    <el-row>
      <el-col :span="4">
        <my-menu :data="sideData" activeIndex="1"></my-menu>
      </el-col>
      <el-col :span="20">
        <my-breadcrumb :data="breadcrumb"></my-breadcrumb>
        <el-form ref="form" label-width="80px" :rules="rules" :model="form">
          <my-component 
            v-for="(comp,index) in formList" 
            :key="index"
            :item="comp.item" 
            :data="comp.data"
            v-model="form[comp.item.name]"
            ></my-component>
          <form-button @submitForm="submitForm('form')" @resetForm="resetForm('form')"></form-button>
        </el-form>

        <my-table 
          @handleCurrentChange="handleCurrentChange($event)"
          :currentPage="currentPage"
          :total="45"
          :rowData="rowData" 
          :columnData="columnData"></my-table>

        <el-card>
          <div slot="header">
            这是head
          </div>
          body
        </el-card>

      </el-col>
    </el-row>
  </main>
</template>

<script>
  import { Button, Form, Row, Col, Card } from 'element-ui'
  import MyMenu from '../../components/MyMenu'
  import FormButton from '../../components/MyComponent/FormButton'
  import MyComponent from '../../components/MyComponent'
  import MyTable from '../../components/MyTable'
  import MyBreadcrumb from '../../components/MyBreadcrumb'
  export default {
    components: {
      ElRow: Row,
      ElCol: Col,
      ElButton: Button,
      ElForm: Form,
      FormButton,
      MyComponent,
      MyTable,
      MyMenu,
      MyBreadcrumb,
      ElCard: Card
    },
    created () {
      this.$emit('setActiveIndex', '1-2')
    },
    data () {
      return {
        breadcrumb: ['首页', '技术'],
        headIndex: '1',
        headData: [{
          key: '1',
          value: '处理事务'
        }, {
          key: '2',
          value: '处理事务'
        }],
        sideData: [{
          key: '1',
          value: '处理事务'
        }, {
          key: '2',
          value: '处理事务'
        }],
        currentPage: 1,
        columnData: [
          {
            name: 'date',
            lable: '日期'
          },
          {
            name: 'name',
            lable: '名称'
          },
          {
            name: 'address',
            lable: '地址'
          }
        ],
        rowData: [{
          date: '2016-05-02',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1518 弄'
        }, {
          date: '2016-05-04',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1517 弄'
        }, {
          date: '2016-05-01',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1519 弄'
        }, {
          date: '2016-05-03',
          name: '王小虎',
          address: '上海市普陀区金沙江路 1516 弄'
        }],
        rules: {
          d: [
            { required: true, message: '请输入活动名称', trigger: 'blur' },
            { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
          ]
        },
        formList: [{
          item: {
            type: 'select',
            label: '1',
            name: 'a',
            placeholder: '请输入。。'
          },
          data: [{
            label: '1',
            value: '1'
          }, {
            label: '2',
            value: '3'
          }]
        }, {
          item: {
            type: 'radio',
            label: '2',
            name: 'b'
          },
          data: [{
            label: '1',
            value: '1'
          }, {
            label: '2',
            value: '3'
          }]
        }, {
          item: {
            type: 'checkbox',
            label: '3',
            name: 'c'
          },
          data: [{
            label: '1',
            value: '1'
          }, {
            label: '2',
            value: '3'
          }]
        }, {
          item: {
            type: 'text',
            label: '4',
            name: 'd',
            placeholder: '请输入。。'
          }
        }, {
          item: {
            type: 'textarea',
            label: '5',
            name: 'e',
            placeholder: '请输入。。'
          }
        }, {
          item: {
            type: 'date',
            label: '5',
            name: 'f',
            placeholder: '请输入。。'
          }
        }],
        form: {
          a: '',
          b: '',
          c: '',
          d: '',
          e: ''
        }
      }
    },
    methods: {
      submitForm (formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            alert('submit!')
          } else {
            console.log('error submit!!')
            return false
          }
        })
      },
      resetForm (formName) {
        // this.$refs[formName].resetFields()
        this.form = {
          d: ''
        }
        alert('reset!')
      },
      handleCurrentChange (current) {
        console.log('currentpage', current)
        return false
      },
      handleSelect (e) {
        debugger
      }
    }
  }
</script>
