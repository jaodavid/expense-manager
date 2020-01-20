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
            <th scope="col">Category</th>
            <th scope="col">Amount</th>
            <th scope="col">Entry Date</th>
            <th scope="col">Date Created</th>
        </tr>
        <tr v-for="expense in expenses" v-bind:key="expense.id" v-on:dblclick="editModal(expense.id)" style="cursor: pointer">
            <th scope="row">{{ expense.category.name }}</th>
            <td>{{ expense.amount }}</td>
            <td>{{ expense.entry_date }}</td>
            <td>{{ expense.created_at}}</td>
        </tr>
    </table>
    <modal
      v-show="isModalVisible"
      @close="closeModal"
      :state="state"
      :title="title"
      :categories="categories"
      :expense="expense"
    />
</div>
</template>
<script>
import modal from './modals/ExpenseModal.vue';
  export default {
    components: {
      modal,
    },
    data() {
      return {
        has_error: false,
        expenses: null,
        categories: null,
        isModalVisible: false,
        state: 'Create',
        title: 'Expense',
        expense: {
          id: '',
          expense_category_id: '',
          amount: '',
          entry_date: '',
          user_id: this.$auth.user().id
        },
        user_id: this.$auth.user().id
      }
    },    
    mounted() {
      this.getExpenses();
      this.getCategories();
    },
    methods: {
      getExpenses() {
        this.$http({
          url: `expense/user/`+this.user_id,
          method: 'GET'
        })
            .then((res) => {
              this.expenses = res.data
            }, () => {
              this.has_error = true
            })
      },
      getExpenseById(id) {
        this.$http({
          url: `expense/${id}`,
          method: 'GET'
        })
            .then((res) => {
              this.expense.id = res.data.id
              this.expense.expense_category_id = res.data.expense_category_id
              this.expense.amount = res.data.amount
              this.expense.entry_date = res.data.entry_date
              this.expense.user_id = res.data.user_id
            }, () => {
              this.has_error = true
            })
      },
      getCategories() {
        this.$http({
          url: `expense-category`,
          method: 'GET'
        })
            .then((res) => {
              this.categories = res.data
            }, () => {
              this.has_error = true
            })
      },
      createModal() {
        this.state = 'Create';
        this.isModalVisible = true;
      },
      editModal(id) {
        this.getExpenseById(id);
        this.state = 'Edit';
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
        this.getExpenses();
        this.clearData();
      },
      clearData() {
        this.expense.id = ''
        this.expense.expense_category_id = ''
        this.expense.amount = ''
        this.expense.entry_date = ''
        this.expense.user_id = this.user_id
      }
    }
  };
</script>