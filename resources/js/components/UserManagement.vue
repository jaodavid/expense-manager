<template>
<div class="container">
    <div>
      <button type="button" class="btn btn-primary float-right pull-right mb-3" v-on:click="createModal()">New</button>
    </div>
    <div class="alert alert-danger" v-if="has_error">
        <p>Error, unable to retrieve this lists.</p>
    </div>
    <table class="table table-bordered mt-5">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col">Date Created</th>
        </tr>
        <tr v-for="user in users" v-bind:key="user.id" v-on:dblclick="editModal(user.id)" style="cursor: pointer">
            <th scope="row">{{ user.name }}</th>
            <td>{{ user.email }}</td>
            <td>{{ user.role.name }}</td>
            <td>{{ user.created_at}}</td>
        </tr>
    </table>
    <modal
      v-show="isModalVisible"
      @close="closeModal"
      :state="state"
      :title="title"
      :roles="roles"
      :user="user"
    />
</div>
</template>
<script>
import modal from './modals/UserModal.vue';
  export default {
    components: {
      modal,
    },
    data() {
      return {
        has_error: false,
        users: null,
        roles: null,
        isModalVisible: false,
        state: 'create',
        title: 'user',
        user: {
          id: '',
          name: '',
          email: '',
          role_id: ''
        }
      }
    },   
    mounted() {
      this.getUsers();
      this.getRoles();
    },    
    methods: {
      getUsers() {
        this.$http({
          url: `user-management`,
          method: 'GET'
        })
            .then((res) => {
              this.users = res.data
            }, () => {
              this.has_error = true
            })
      },
      getUsersById(id) {
        this.$http({
          url: `user-management/${id}`,
          method: 'GET'
        })
            .then((res) => {
              this.user.id = res.data.id
              this.user.name = res.data.name
              this.user.email = res.data.email
              this.user.role_id = res.data.role_id
            }, () => {
              this.has_error = true
            })
      },
      getRoles() {
        this.$http({
          url: `role`,
          method: 'GET'
        })
            .then((res) => {
              this.roles = res.data
            }, () => {
              this.has_error = true
            })
      },
      createModal() {
        this.state = 'create';
        this.isModalVisible = true;
      },
      editModal(id) {
        this.getUsersById(id);
        this.state = 'edit';
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
        this.getUsers();
        this.clearUserData();
      },
      clearUserData() {
        this.user.id = ''
        this.user.name = ''
        this.user.email = ''
        this.user.role_id = ''
      }
    }
  };
</script>