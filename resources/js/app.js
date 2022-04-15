/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Dashboard from '../js/components/Dashboard.vue'
import ChooseWorkspace from '../js/pages/ChooseWorkspace.vue'
import WorkspaceDashboard from '../js/pages/WorkspaceDashboard.vue'
import BoardPage from '../js/pages/BoardPage.vue'
import App from '../js/App.vue'
import Vue from 'vue';
import VueRouter from 'vue-router'
import todoStore from './store/store';


if ( !! document.querySelector('#app') ) {

  const store = todoStore
  
  Vue.use(VueRouter);
  
  const routes = [
    {path: '/', component: ChooseWorkspace},
    {path: '/workspace/:workspace', component: WorkspaceDashboard},
    {path: '/workspace/:workspace/board/:board', component: BoardPage},
    {path: '/legacy', component: Dashboard},
  ]
  const router = new VueRouter({routes})
  
  const files = require.context('./', true, /\.vue$/i)
  files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
  
  const app = new Vue({
    el: "#app",
    router: router,
    store: store,
    render: h => h(App),
  })

}