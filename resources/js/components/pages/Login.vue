<template>
<div>
  <nav class="navbar navbar-dark bg-dark justify-content-between">
    <a class="navbar-brand">Expense Manager</a>
    <div>
      <div class="btn btn-outline-info my-2 my-sm-0"><router-link to="/register">Register</router-link></div>
    </div>
  </nav>
  <div class="container mt-30 col-md-4">
      <div class="card card-info">
          <div class="card-header text-white bg-info">Login</div>            
          <div class="card-body">
              <div class="alert alert-danger" v-if="has_error">
                  <p>Oops, something went wrong, unable to login at this time.</p>
              </div>
              <form autocomplete="off" @submit.prevent="login" method="post">
                  <div class="form-group">
                      <label for="email">E-mail</label>
                      <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="email" required>
                  </div>
                  <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" id="password" placeholder="********" class="form-control" v-model="password" required>
                  </div>
                  <button type="submit" class="btn btn-info">Submit</button>
              </form>
          </div>
      </div>
  </div>
</div>
</template>
<script>
  export default {
    data() {
      return {
        email: null,
        password: null,
        has_error: false
      }
    },    mounted() {
      //
    },    methods: {
      login() {
        var redirect = this.$auth.redirect()
        var app = this
        this.$auth.login({
          params: {
            email: app.email,
            password: app.password
          },
          success: function() {
            const redirectTo = redirect ? redirect.from.name : 
            this.$auth.user().role_id === 1 ? 'My Expenses (Admin)' : 'My Expenses'            
            this.$router.push({name: redirectTo})
          },
          error: function() {
            app.has_error = true
          },
          rememberMe: true,
          fetchUser: true
        })
      }
    }
  };
</script>
<style type="text/css">
  .mt-30 {
    margin-top: 50px;
  }
</style>