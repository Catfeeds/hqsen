<template>
  <div class="wrapper">
    <my-menu 
      mode="horizontal" 
      :data="headData" 
      theme="dark" 
      :activeIndex="headIndex" 
      @handleSelect="handleSelect($event)"></my-menu>
    <router-view></router-view>
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
      if (token) {
        this.$router.push('/home')
      } else {
        this.$router.push('/login')
      }
    },
    data () {
      return {
        headData: [{
          key: 'mpppppppp',
          value: 'jjjjjjjjjj'
        }, {
          key: 'comppppppp',
          value: 'eee'
        }, {
          key: 'comppppp',
          value: 'fff'
        }],
        headIndex: 'mpppppppp'
      }
    },
    methods: {
      handleSelect (key) {
        console.log(key)
      }
    }
  }
</script>
