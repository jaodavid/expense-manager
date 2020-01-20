<script>
  export default {
    props: ['state', 'title', 'roles', 'user'],
    name: 'modal',
    data: function () {
      return {
        userData: this.user
      }
    },
    methods: {
      close() {
        this.$emit('close');
      },
      save() {
        let params = this.userData;
        let currentObj = this;
        if (params.id) {
          this.axios.put(`/user-management/${params.id}`, params)
          .then(function (response) {
              currentObj.output = response.data;
              currentObj.close()
          })
          .catch(function (error) {
              currentObj.output = error;
          });
        } else {
          this.axios.post(`/user-management`, params)
          .then(function (response) {
              currentObj.output = response.data;
              currentObj.close()
          })
          .catch(function (error) {
              currentObj.output = error;
          });
        }
      },
      deleteUser() {
        let params = this.userData;
        let currentObj = this;
        this.axios.delete(`/user-management/${params.id}`)
          .then(function (response) {
              currentObj.output = response;
              currentObj.close()
          })
          .catch(function (error) {
              currentObj.output = error;
          });
      }
    },
  };
</script>
<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header">
              {{ state }} {{ title }}
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              <form autocomplete="off" v-on:submit.prevent="save" method="post">
                <div class="form-group">
                  <input v-if="userData.id" type="hidden" id="id" name="custId" v-model="userData.id">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" class="form-control" placeholder="Sam Carter" v-model="userData.name">
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="userData.email">
                </div>
                <div class="form-group">
                  <label for="role">Role</label>
                  <select id="role" class="form-control" v-model="userData.role_id">
                    <option disabled value="">Please select one</option>
                    <option v-for="role in roles" v-bind:value="role.id">{{ role.name }}</option>
                  </select>
                </div>
                <div class="float-left pull-left" v-if="userData.id">
                  <div class="btn btn-danger" @click="deleteUser">delete</div>
                </div>
                <div class="float-right pull-right">
                  <div class="btn btn-info" @click="$emit('close')">cancel</div>
                  <button type="submit" class="btn btn-success">save</button>
                </div>
              </form>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
<style type="text/css">
  .modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 600px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>