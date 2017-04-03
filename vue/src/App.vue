<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
  import Fetch from './Fetch'
  export default {
    created () {
      const token = sessionStorage.getItem('token')
      this.getBasecInfo()
      if (!token) {
        this.$router.push('/login')
      }
    },
    data () {
      return {
        basicInfo: {}
      }
    },
    methods: {
      getBasecInfo () {
        Fetch('configData').then(response => {
          this.basicInfo = response.data
          localStorage.setItem('basicInfo', JSON.stringify(response.data))
        })
      }
    }
  }
</script>
