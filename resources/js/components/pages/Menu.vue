<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
      <h1 class="h2">{{ pageTitle }}</h1>
      <div v-if="$auth.check()" >
        <div class="btn btn-outline-info btn-sm"><router-link to="/change-password">Change Password</router-link></div>
        <button class="btn btn-outline-info btn-sm" @click.prevent="$auth.logout()">Logout</button>
      </div>
    </div>
</template>
<script>
import router from '../../router'
  export default {
    data() {
      return {
        pageTitle: 'Dashboard'
      }
    },
    mounted() {
      this.getTitle()
    },
    watch:{
    $route (to, from){
            this.getTitle()
        }
    },
    methods: {
      getTitle() {
          this.pageTitle = this.$route.name
      }
    }
  };
</script>