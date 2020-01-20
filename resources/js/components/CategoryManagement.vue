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
            <th scope="col">Description</th>
            <th scope="col">Date Created</th>
        </tr>
        <tr v-for="cat in categories" v-bind:key="cat.id" v-on:dblclick="editModal(cat.id)" style="cursor: pointer">
            <th scope="row">{{ cat.name }}</th>
            <td>{{ cat.description }}</td>
            <td>{{ cat.created_at}}</td>
          </tr>
    </table>
    <modal
      v-show="isModalVisible"
      @close="closeModal"
      :state="state"
      :title="title"
      :category="category"
    />
  </div>
</template>
<script>
import modal from './modals/CategoryModal.vue';
  export default {
    components: {
      modal,
    },
    data() {
      return {
        has_error: false,
        categories: null,
        isModalVisible: false,
        state: 'Create',
        title: 'Category',
        category: {
          id: '',
          name: '',
          description: '',
        }
      }
    },
    mounted() {
      this.getCategories()
    },
    methods: {
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
      getCategoryById(id) {
        this.$http({
          url: `expense-category/${id}`,
          method: 'GET'
        })
            .then((res) => {
              this.category.id = res.data.id
              this.category.name = res.data.name
              this.category.description = res.data.description
            }, () => {
              this.has_error = true
            })
      },
      createModal() {
        this.state = 'Create';
        this.isModalVisible = true;
      },
      editModal(id) {
        this.getCategoryById(id);
        this.state = 'Edit';
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
        this.getCategories();
        this.clearData();
      },
      clearData() {
        this.category.id = ''
        this.category.name = ''
        this.category.description = ''
      }
    }
  };
</script>