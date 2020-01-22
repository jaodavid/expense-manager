<script>
  // Import required dependencies 
  import 'bootstrap/dist/css/bootstrap.css';
  
  // Import this component
  import datePicker from 'vue-bootstrap-datetimepicker';
  
  // Import date picker css
  import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
  export default {
    props: ['state', 'title', 'categories', 'expense'],
    name: 'modal',
    components: {
      datePicker
    },
    data: function () {
      return {
        expenseData: this.expense,
        date: new Date(),
        options: {
          format: 'YYYY/MM/DD',
          useCurrent: false,
        },
        error: [],
        hasError: false
      }
    },
    methods: {
      close() {
        this.error = []
        this.hasError = false
        this.$emit('close');
      },
      save() {
        let params = this.expenseData;
        let currentObj = this;
        if (params.id) {
          this.axios.put(`/expense/${params.id}`, params)
          .then(function (response) {
              if (response.data.error) {
                currentObj.error = response.data.error;
                currentObj.hasError = true
              } else {
                currentObj.output = response.data;
                currentObj.close()
                currentObj.hasError = false
              }
          })
          .catch(function (error) {
              currentObj.hasError = true
              currentObj.error = {'message': [error]};
          });
        } else {
          this.axios.post(`/expense`, params)
          .then(function (response) {
              if (response.data.error) {
                currentObj.error = response.data.error;
                currentObj.hasError = true
              } else {
                currentObj.output = response.data;
                currentObj.close()
                currentObj.hasError = false
              }
          })
          .catch(function (error) {
              currentObj.hasError = true
              currentObj.error = {'message': [error]};
          });
        }
      },
      deleteExpense() {
        let params = this.expenseData;
        let currentObj = this;
        this.axios.delete(`/expense/${params.id}`)
          .then(function (response) {
              if (response.data.error) {
                currentObj.error = response.data.error;
                currentObj.hasError = true
              } else {
                currentObj.output = response.data;
                currentObj.close()
                currentObj.hasError = false
              }
          })
          .catch(function (error) {
              currentObj.hasError = true
              currentObj.error = {'message': [error]};
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
              <h3 class="h3">{{ state | capitalize }} {{ title | capitalize }}</h3>
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              <div v-if="hasError" class="alert alert-danger" role="alert">
                <div v-for="err in error">
                  <span v-for="e in err">{{ e }}</span>
                </div>
              </div>
              <form autocomplete="off" v-on:submit.prevent="save" method="post">
                <div class="form-group">
                  <input v-if="expenseData.id" type="hidden" id="id" name="id" v-model="expenseData.id">
                </div>
                <div class="form-group">
                  <input type="hidden" id="user_id" name="user_id" v-model="expenseData.user_id">
                </div>
                <div class="form-group">
                  <label for="category">Category</label>
                  <select id="category" class="form-control" v-model="expenseData.expense_category_id">
                    <option disabled value="">Please select one</option>
                    <option v-for="cat in categories" v-bind:value="cat.id">{{ cat.name }}</option>
                  </select>
                </div>
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" id="amount" class="form-control" placeholder="100.00" v-model="expenseData.amount">
                </div>
                <div class="form-group">
                    <label for="entry_date">Entry Date</label>
                    <date-picker placeholder="YYYY/MM/DD" name="entry_date" id="entry_date" :config="options" v-model="expenseData.entry_date"></date-picker>
                </div>
                <div class="float-left pull-left" v-if="expenseData.id">
                  <div class="btn btn-danger" @click="deleteExpense">delete</div>
                </div>
                <div class="float-right pull-right">
                  <div class="btn btn-info" @click="close">cancel</div>
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