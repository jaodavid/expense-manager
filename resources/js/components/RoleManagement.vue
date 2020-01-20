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
            <th scope="col">Display Name</th>
            <th scope="col">Email</th>
            <th scope="col">Date Created</th>
        </tr>
        <tr v-for="role in roles" v-bind:key="role.id" v-on:dblclick="editModal(role.id)" style="cursor: pointer">
            <th scope="row">{{ role.name }}</th>
            <td>{{ role.description }}</td>
            <td>{{ role.created_at}}</td>
        </tr>
    </table>
    <modal
      v-show="isModalVisible"
      @close="closeModal"
      :state="state"
      :title="title"
      :role="role"
    />
  </div>
</template>
<script>
import modal from './modals/RoleModal.vue';
  export default {
    components: {
      modal,
    },
    data() {
      return {
        has_error: false,
        roles: null,
        isModalVisible: false,
        state: 'Create',
        title: 'Role',
        role: {
          id: '',
          name: '',
          description: '',
        }
      }
    },
    mounted() {
      this.getRoles()
    },
    methods: {
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
      getRoleById(id) {
        this.$http({
          url: `role/${id}`,
          method: 'GET'
        })
            .then((res) => {
              this.role.id = res.data.id
              this.role.name = res.data.name
              this.role.description = res.data.description
            }, () => {
              this.has_error = true
            })
      },
      createModal() {
        this.state = 'Create';
        this.isModalVisible = true;
      },
      editModal(id) {
        this.getRoleById(id);
        this.state = 'Edit';
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
        this.getRoles();
        this.clearData();
      },
      clearData() {
        this.role.id = ''
        this.role.name = ''
        this.role.description = ''
      }
    }
  };
</script>