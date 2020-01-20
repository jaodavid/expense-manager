<template>
  <div class="container-fluid">
    <div class="row">
        <nav id="nav-sidebar" class="col-md-2 d-none d-md-block bg-dark text-info sidebar">
            <div class="sidebar-sticky">
                <div class="align-items-center pb-2 mb-3 mt-3 border-bottom">
                    <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Avatar" class="avatar mb-2"> 
                    <h5 class="h5">{{ userName }}</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <div class="nav-link">
                            <span data-feather="home"></span>
                            <router-link to="/dashboard" class="text-info">Dashboard</router-link>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="nav-link">
                            <span data-feather="shopping-cart"></span>
                            Expense Management
                        </div>
                        <div class="ml-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <div class="nav-link active" href="#">
                                        <span data-feather="home"></span>
                                        <router-link to="/dashboard/expense-management" class="text-info">Expenses</router-link>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <navigationMenu></navigationMenu>

            <div v-if="isDashboard">
              <div class="row" v-if="hasChartData">
                <div class="col-md-6 ml-sm-auto col-lg-6">
                  <graph-pie
                          :width="500"
                          :height="500"
                             :padding-top="100"
                             :padding-bottom="100"
                             :padding-left="100"
                             :padding-right="100"
                          :values="values"
                          :names="names"
                          :active-index="[ 0, 2 ]"
                          :active-event="'click'"
                          :show-text-type="'outside'"
                          :data-format="dataFormat">
                      <legends :names="names"></legends>
                      <tooltip :names="names"></tooltip>
                  </graph-pie>
                </div>
                <div class="col-md-6 ml-sm-auto col-lg-6">
                  <table class="table mt-5">
                      <tr>
                          <th scope="col">Expense Categories</th>
                          <th scope="col">Total</th>
                      </tr>
                      <tr v-for="myexp in myExpenses" style="margin-bottom: 5px;">
                          <td>{{ myexp.name }}</td>
                          <td>{{ myexp.sum }}</td>
                      </tr>
                  </table>
                </div>
              </div>

              <div v-else class="alert alert-warning" role="alert">
                My Expenses data unavailable!
              </div>
            </div>

            <router-view></router-view>

        </main>
      </div>
    </div>
</template>

<script>
import navigationMenu from './Menu.vue'
import GraphPie from 'vue-graph/src/components/pie.js'

  export default {
    mounted() {
      this.getChart();
      this.checkRoute();
    },
    data() {
      return {
        values: [],
        names: [],
        userName: this.$auth.user().name,
        isDashboard: true,
        hasChartData: true,
        myExpenses: null
      }
    },
    components: {
      navigationMenu,
      GraphPie
    },
    watch:{
    $route (to, from){
            this.checkRoute()
            this.getChart()
        }
    },
    methods: {
        dataFormat: function(a, b) {
            if(b) return b + "%";
            return a;
        },
        getChart() {
            this.$http({
              url: `expense/chart/`+this.$auth.user().id,
              method: 'GET'
            })
            .then((res) => {
                this.values = res.data.values
                this.names = res.data.names
                this.myExpenses = res.data.data
                if (res.data.values.length > 0 && res.data.names.length > 0) {
                  this.hasChartData = true
                } else {
                  this.hasChartData = false
                }
            }, () => {
              this.has_error = true
            })
        },
        checkRoute() {
          if (this.$route.name == 'My Expenses') {
            this.isDashboard = true;
          } else {
            this.isDashboard = false;
          }
        }
    }
  };
</script>

<style type="text/css">
    .avatar {
      vertical-align: middle;
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }

    #nav-sidebar {
      position:fixed;
      width: 20%;
      z-index: 1000;
      left: 0;
      top: 0;
      border-right: 1px solid #162636;
      height: 100%;
    }
</style>