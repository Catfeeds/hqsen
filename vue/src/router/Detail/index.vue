<template>
  <div class="content-wrapper">
    <my-breadcrumb :data="basicInfo.breadcrumb"/>
    <el-row>
      <el-col :span="8" :offset="8">
        <el-form label-width="110px" :model="formData" :rules="basicInfo.rules">
          <my-component 
            v-for="(item,index) in basicInfo.formList" 
            :key="index"
            :item="item" 
            v-model="formData[item.name]"/>
        </el-form>
      </el-col>
    </el-row>
  </div>

</template>

<script>
  import { Form, Row, Col } from 'element-ui'
  import MyComponent from '../../components/MyComponent'
  import MyBreadcrumb from '../../components/MyBreadcrumb'
  import { getConfig } from './config'
  import Fetch from '../../Fetch'
  import { configData } from '../../commonData'

  export default {
    components: {
      ElRow: Row,
      ElCol: Col,
      ElForm: Form,
      MyComponent,
      MyBreadcrumb
    },
    created () {
      this.setBasicInfo()
    },
    data () {
      return {
        type: '',
        formData: {},
        basicInfo: {
          rules: {},
          formList: [],
          breadcrumb: []
        }
      }
    },
    watch: {
      '$route': 'setBasicInfo'
    },
    methods: {
      setBasicInfo () {
        const type = this.$route.params.type
        const id = this.$route.query.id
        const config = getConfig(configData())
        this.basicInfo = config[type]
        this.type = type
        this.getInit(id)
      },
      getInit (id) {
        Fetch(this.basicInfo.detailUrlKey, { id }).then(response => {
          const data = response.data
          if (this.type === 'order_info__kezi_list') {
            this.basicInfo.formList.map(item => {
              if (item.name === 'order_hotel') {
                item.data = data.order_hotel
                data.order_hotel = data.order_hotel[0].value
              }
              if (item.name === 'order_area') {
                item.data = data.order_area
                data.order_area = data.order_area[0].value
              }
            })
          }
          this.formData = data
        })
      }
    }
  }
</script>
