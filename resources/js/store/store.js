import Vuex from 'vuex';
import TodoAxios from '../Axios'
import Vue from 'vue'

Vue.use(Vuex)

const axios = new TodoAxios()

const todoStore = new Vuex.Store({
  state: {
    workspaces: [],
    currentWorkspace: null,
    initialLoading: true,
    loading: false,
    newBoardPopupIsOpen: false,
    sidebarIsOpen: true,
  },
  getters: {
    getCurrentWorkspace: (state) => () => {
      return state.currentWorkspace
    },
  },
  actions: {
    setCurrentWorkspace ({commit}, {workspace_id}) {
      commit('setCurrentWorkspace', {workspace_id})
    },
    storeBoard ({commit}, {name, workspace_id}) {
      commit('storeBoard', {name, workspace_id})
      commit('closeNewBoardPopup')
    },
    reset( {commit} ) {
      commit('resetCurrentWorkspace')
    }
  },
  mutations: {
    async init (state) {
      const response = await axios.getUserWorkspaces()
      if (response.status == 200) {
        const workspaces = response.data.data
        if (workspaces && workspaces.length > 0) {
          state.workspaces = workspaces
        }
      }
      state.initialLoading = false
    },
    async resetCurrentWorkspace (state) {
      state.currentWorkspace = null
    },
    async storeBoard (state, {name, workspace_id}) {
      const response = await axios.storeBoard({name, workspace_id})
      console.log(response)
      if (response.status == 201) {
        state.currentWorkspace.boards.push(response.data.data)
      }
    },
    async setCurrentWorkspace (state, { workspace_id }) {
      state.currentWorkspace = state.workspaces.find(workspace => workspace.id == workspace_id)
    },
    async openNewBoardPopup (state) {
      state.newBoardPopupIsOpen = true
    },
    async closeNewBoardPopup (state) {
      state.newBoardPopupIsOpen = false
    },
    async openSidebar (state) {
      state.sidebarIsOpen = true
    },
    async closeSidebar (state) {
      state.sidebarIsOpen = false
    },
  }
})

export default todoStore