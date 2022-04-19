import Vuex from 'vuex';
import TodoAxios from '../Axios'
import Vue from 'vue'

Vue.use(Vuex)

const axios = new TodoAxios()

const todoStore = new Vuex.Store({
  state: {
    workspaces: [],
    currentWorkspace: null,
    currentBoard: null,
    initialLoading: true,
    loading: false,
    newBoardPopupIsOpen: false,
    newMemberPopupIsOpen: false,
    newWorkspacePopupIsOpen: false,
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
    storeWorkspace({commit}, {name}) {
      commit('storeWorkspace', {name})
      commit('closeNewWorkspacePopup')
    },
    reset( {commit} ) {
      commit('resetCurrents')
      commit('closeSidebar')
    }
  },
  mutations: {
    async init (state, {routeParams}) {
      const response = await axios.getUserWorkspaces()
      if (response.status == 200) {
        const workspaces = response.data.data
        if (workspaces && workspaces.length > 0) {
          state.workspaces = workspaces
        }
      }
      // Setting up default workspace and board to be the ones in the url
      if (routeParams.workspace) {
        state.currentWorkspace = state.workspaces.find(workspace => workspace.id == routeParams.workspace) ?? null
      }
      if (routeParams.board && state.currentWorkspace) {
        state.currentBoard = state.currentWorkspace.boards.find(board => board.id == routeParams.board) ?? null
      }
      
      state.initialLoading = false
    },
    async resetCurrents (state) {
      state.currentWorkspace = null
      state.currentBoard = null
    },
    async storeBoard (state, {name, workspace_id}) {
      const response = await axios.storeBoard({name, workspace_id})
      if (response.status == 201) {
        state.currentWorkspace.boards.push(response.data.data)
      }
    },
    async storeBoard (state, {name}) {
      const response = await axios.storeMember({name})
      if (response.status == 201) {
        state.currentWorkspace.members.push(response.data.data)
      }
    },
    async storeWorkspace (state, {name}) {
      const response = await axios.storeWorkspace({name})
      if (response.status == 201) {
        state.workspaces.push(response.data.data)
      }
    },
    async setCurrentWorkspace (state, { workspace_id }) {
      state.currentWorkspace = state.workspaces.find(workspace => workspace.id == workspace_id)
    },
    async setCurrentBoard (state, {board_id}) {
      state.currentBoard = state.currentWorkspace.boards.find(board => board.id == board_id)
    },
    async openNewBoardPopup (state) {
      state.newBoardPopupIsOpen = true
    },
    async closeNewBoardPopup (state) {
      state.newBoardPopupIsOpen = false
    },
    async openNewMemberPopup (state) {
      state.newMemberPopupIsOpen = true
    },
    async closeNewMemberPopup (state) {
      state.newMemberPopupIsOpen = false
    },
    async openNewWorkspacePopup (state) {
      state.newWorkspacePopupIsOpen = true
    },
    async closeNewWorkspacePopup (state) {
      state.newWorkspacePopupIsOpen = false
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