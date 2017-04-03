<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
  export default {
    created () {
      const token = sessionStorage.getItem('access_token')
      if (!token) {
        this.$router.push('/login')
      }
    }
  }
</script>
