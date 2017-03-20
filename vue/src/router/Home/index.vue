<template>
  <main>
    <el-header></el-header>
    <!--<el-sidebar style="width: 200px;"></el-sidebar>-->
    <el-button type="primary" @click.native="hello">primary</el-button>
    <el-form label-width="80px" :model="form">
      <my-input label="jjjj" name="aaa" v-model="form.aa"></my-input>
      <my-select label="ddd" name="ddd" :options="selectData" v-model="form.bb"></my-select>
      <my-button @onSubmit="onSubmit"></my-button>
    </el-form>
  </main>
</template>

<script>
  import { Button, Message, Form } from 'element-ui'
  import Header from '../../components/Header'
  import Sidebar from '../../components/Sidebar'
  import FormInput from '../../components/Form/FormInput'
  import FormSelect from '../../components/Form/FormSelect'
  import FormButton from '../../components/Form/FormButton'
  export default {
    components: {
      ElButton: Button,
      ElHeader: Header,
      ElSidebar: Sidebar,
      FormInput,
      FormSelect,
      FormButton
    },
    data () {
      return {
        form: {
          aa: '',
          bb: ''
        },
        selectData: [{
          label: '11',
          value: '111'
        }]
      }
    },
    methods: {
      hello () {
        Message('Hello Vue')
      }
    }
  }
</script>
