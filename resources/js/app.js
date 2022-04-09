/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import HomeDashboard from '../js/components/HomeDashboard.vue'
import Dashboard from '../js/components/Dashboard.vue'
import App from '../js/App.vue'
import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router'
import TodoAxios from './Axios';


if ( !! document.querySelector('#app') ) {

    Vue.use(VueRouter);
    Vue.use(Vuex)
    
    const routes = [
        {path: '/', component: HomeDashboard},
        {path: '/legacy', component: Dashboard},
    ]
    const router = new VueRouter({routes})

    const store = new Vuex.Store({
        state: {
            workspaces: [],
            currentWorkspaceId: null,
            loading: true,
        },
        getters: {
            getCurrentWorkspace: (state) => () => {
                const workspace = state.workspaces.find(workspace => workspace.id = state.currentWorkspaceId)
                return workspace
            }
        },
        mutations: {
            async findWorkspaces (state) {
                state.loading = true

                const axios = new TodoAxios()

                const response = await axios.get('/workspace')
                const workspaces = response.data.data

                if (workspaces.length > 0) {
                    state.workspaces = workspaces
                    state.currentWorkspaceId = state.workspaces[0]?.id ?? null
                }

                state.loading = false
            },
            async setCurrentWorkspace (state, { id }) {
                state.loading = true
                state.currentWorkspaceId = id
                state.loading = false
            }
        }
    })
    
    
    const files = require.context('./', true, /\.vue$/i)
    files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
    
    const app = new Vue({
        el: "#app",
        router: router,
        store: store,
        render: h => h(App),
    })

}