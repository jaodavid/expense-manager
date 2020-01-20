<template>
<div>
  <nav class="navbar navbar-dark bg-dark justify-content-between">
    <a class="navbar-brand">Expense Manager</a>
    <div>
      <div class="btn btn-outline-info my-2 my-sm-0" @click="dashboard">My Dashboard</div>
    </div>
  </nav>
    <div class="container mt-30 col-md-4">
        <div class="card card-default">
            <div class="card-header text-white bg-info">Change Password</div>
            <div class="card-body">
                <div class="alert alert-danger" v-if="has_error">
                    <p>Error, unable to change password at this time. If the problem persists, please contact an administrator or double check your request.</p>
                </div>
                <form autocomplete="off" @submit.prevent="changePass" method="post">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" class="form-control" v-model="changePassData.password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input type="password" id="confirm_password" class="form-control" v-model="changePassData.confirm_password">
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
        changePassData: {
            user_id: this.$auth.user().id,
            password: '',
            confirm_password: ''
        },
        has_error: false,
      }
    },    
    methods: {
      changePass() {
        let params = this.changePassData;
        let currentObj = this;
        this.axios.post(`/change-password`, params)
        .then(function (response) {
            if (response.data.error) {
              currentObj.has_error = true;
            } else {
              currentObj.dashboard();
            }
        })
        .catch(function (error) {
          currentObj.has_error = true;
        });
      },
      dashboard() {
        var redirect = this.$auth.redirect()
        const redirectTo = redirect ? redirect.from.name : 
        this.$auth.user().role_id === 1 ? 'My Expenses (Admin)' : 'My Expenses'            
        this.$router.push({name: redirectTo})
      }
    }
  };
</script>

<style type="text/css">
  .mt-30 {
    margin-top: 50px;
  }
</style>