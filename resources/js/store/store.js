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
    apiCallsQueue: 0,
    loading: false,
    
    newBoardPopupIsOpen: false,
    newWorkspacePopupIsOpen: false,
    sidebarIsOpen: true,
  },
  getters: {
    currentWorkspace: (state) => () => {
      return state.currentWorkspace
    },
    initialLoading: (state) => () => {
      return state.initialLoading
    }
  },
  actions: {
    async setCurrentWorkspace ({commit, state}, {workspaceId}) {
      commit('incrementApiCallsQueue')
      const found = state.workspaces.find(workspace => workspace.id == workspaceId) ?? null
      const currentWorkspace = (found) ? await (await axios.getWorkspace(workspaceId)).data.data : null
      if (currentWorkspace) commit('setCurrentWorkspace', {workspace: currentWorkspace})
      commit('decrementApiCallsQueue')
    },
    async setCurrentBoard ({commit, state}, {boardId}) {
      commit('incrementApiCallsQueue')
      const found = state.currentWorkspace.boards.find(board => board.id == boardId) ?? null
      const currentBoard = (found) ? await (await axios.getBoard(boardId)).data.data : null
      if (currentBoard) commit('setCurrentBoard', {board: currentBoard})
      commit('decrementApiCallsQueue')
    },
    async storeBoard ({commit}, {name, workspace_id}) {
      commit('incrementApiCallsQueue')
      const response = await axios.storeBoard({name, workspace_id})
      if (response.status == 201) {
        commit('storeBoard', {board: response.data.data})
        commit('closeNewBoardPopup')
      }
      commit('decrementApiCallsQueue')
    },
    async storeWorkspace({commit}, {name}) {
      const response = await axios.storeWorkspace({name})
      if (response.status == 201) {
        commit('storeWorkspace', {workspace: response.data.data})
        commit('closeNewWorkspacePopup')
      }
    },
    reset( {commit} ) {
      commit('resetCurrents')
      commit('closeSidebar')
    },
    async init( {commit, dispatch, state}, {workspaceId, boardId} ) {
      const response = await axios.getUserWorkspaces()
      if (response.status == 200) {
        const workspaces = response.data.data
        if (workspaces && workspaces.length > 0) {
          commit('getWorkspaces', {workspaces})
        }
      }
      if (workspaceId) {
        await dispatch('setCurrentWorkspace', {workspaceId})
      }
      if (boardId) {
        await dispatch('setCurrentBoard', {boardId})
      }
      const removeInitialLoading = () => {
        setTimeout(() => {
          if (state.apiCallsQueue > 0) {
            removeInitialLoading()
          } else {
            commit('removeInitialLoading')
          }
        }, 200)
      }
      removeInitialLoading()
    }
  },
  mutations: {
    async removeInitialLoading (state) {
      state.initialLoading = false
    },
    incrementApiCallsQueue (state) {
      let queue = state.apiCallsQueue
      state.apiCallsQueue = queue + 1
    },
    decrementApiCallsQueue (state) {
      let queue = state.apiCallsQueue
      state.apiCallsQueue = queue - 1
    },
    async getWorkspaces (state, {workspaces}) {
      state.workspaces = workspaces
    },
    async resetCurrents (state) {
      state.currentWorkspace = null
      state.currentBoard = null
    },
    async storeBoard (state, {board}) {
      state.currentWorkspace.boards.push(board)
    },
    async storeWorkspace (state, {workspace}) {
      state.workspaces.push(workspace)
    },
    async setCurrentWorkspace (state, { workspace }) {
      state.currentWorkspace = workspace
    },
    async setCurrentBoard (state, {board}) {
      state.currentBoard = board
    },
    async openNewBoardPopup (state) {
      state.newBoardPopupIsOpen = true
    },
    async closeNewBoardPopup (state) {
      state.newBoardPopupIsOpen = false
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