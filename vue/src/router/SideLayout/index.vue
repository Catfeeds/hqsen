<template>
  <div class="wrapper">
    <my-menu :activeIndex="activeIndex"/>
    <router-view @setActiveIndex="setActiveIndex($event)"></router-view>
  </div>
</template>

<script>
  import MyMenu from '../../components/MyMenu'
  export default {
    components: {
      MyMenu
    },
    data () {
      return {
        activeIndex: ''
      }
    },
    methods: {
      setActiveIndex (key) {
        const keyGroup = key.split('__')
        this.activeIndex = keyGroup.length > 1 ? keyGroup[1] : keyGroup[0]
      }
    }
  }
</script>
