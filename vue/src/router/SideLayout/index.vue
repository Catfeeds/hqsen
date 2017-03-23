<template>
  <div class="wrapper">
    <my-menu :activeIndex="activeIndex"></my-menu>
    <router-view @setActiveIndex="setActiveIndex($event)"></router-view>
  </div>
</template>

<script>
  import MyMenu from '../../components/MyMenu'
  export default {
    components: {
      MyMenu
    },
    created () {
      const token = sessionStorage.getItem('token')
      if (!token) {
        this.$router.push('/login')
      }
    },
    data () {
      return {
        activeIndex: ''
      }
    },
    methods: {
      setActiveIndex (key) {
        this.activeIndex = key
      }
    }
  }
</script>
