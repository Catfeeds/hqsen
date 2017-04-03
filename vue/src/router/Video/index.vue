<template>
  <div class="video-container">
    <video class="video-component" ref="videoComponent" loop autoplay poster="" >
      <source :src="src">
    </video>
    <span class="video-center-play"></span>
    <span class="video-loading">正在加载中</span>
    <span class="video-wrong">该视频不支持播放哦！</span>
    <div class="video-control">
      <span class="video-play-btn" @click="controlAudio('play')"></span>
      <span class="video-pause-btn" @click="controlAudio('pause')"></span>
      <span class="video-all-time">{{ currentTime | formatTime }}/{{ allTime | formatTime }}</span>
      <input  
        class="video-progress" 
        type="range"
        @change="controlAudio('changeCurrentTime',value)" 
        :value="currentTime">
      <span 
        class="video-muted" 
        @click="controlAudio('muted')"></span>
      <input 
        class="video-voice" 
        type="range"
        @change="controlAudio('changeVolume',value)"  
        :value="volume"/>
      <span class="video-fullscreen"></span>
    </div>
  </div>
</template>

<style>
.video-container {
  width: 100%;
  height: 100%;
  position: relative;
}
.video-container .video-component {}
.video-center-play,
.video-loading,
.video-wrong {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
}
.video-container .video-control {
  position: absolute;
  bottom: 0;
  width: 100%;
  display: flex;
}
.video-control .video-play-btn {}
.video-control .video-progress {}
.video-control .video-voice {}
.video-control .video-fullscreen {}
</style>

<script>
  export default {
    props: {
      src: String,
      controls: Object,
      options: Object
    },
    data () {
      return {
        isPlay: false,
        isMuted: false,
        volume: 100,
        allTime: 0,
        currentTime: 0
      }
    },
    filter: {
      formatTime (time) {
        const second = Math.floor(time % 60)
        let minite = Math.floor(time / 60)
        // let hour
        // if(minite > 60) {
        //   hour = minite / 60
        //   minite = minite % 60
        //   return `${Math.floor(hour)}:${Math.floor(minite)}:${Math.floor(second)}`
        // }
        return `${minite}:${second >= 10 ? second : `0${second}`}`
      }
    },
    methods: {
      controlAudio (type, value) {
        // const { src } = this
        const audio = this.$refs.videoComponent
        switch (type) {
          case 'allTime':
            this.allTime = audio.duration
            break
          case 'play':
            audio.play()
            this.isPlay = true
            break
          case 'pause':
            audio.pause()
            this.isPlay = false
            break
          case 'muted':
            this.isMuted = !audio.muted
            audio.muted = !audio.muted
            break
          case 'changeCurrentTime':
            this.currentTime = value
            audio.currentTime = value
            if (value === audio.duration) {
              this.isPlay = false
            }
            break
          case 'getCurrentTime':
            this.currentTime = audio.currentTime
            if (audio.currentTime === audio.duration) {
              this.isPlay = false
            }
            break
          case 'changeVolume':
            audio.volume = value / 100
            this.volume = value
            this.isMuted = !value
            break
          case 'requestFullscreen':
            audio.requestFullscreen()
        }
      }
    }
  }
</script>
