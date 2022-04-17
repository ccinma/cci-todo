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
    newLaneFormIsOpen: false,
    sidebarIsOpen: true,
  },

  getters: {
    workspaces: (state) => () => {
      return state.workspaces
    },
    currentWorkspace: (state) => () => {
      return state.currentWorkspace
    },
    currentBoard: (state) => () => {
      return state.currentBoard
    },
    sidebarIsOpen: (state) => () => {
      return state.sidebarIsOpen
    },
    newWorkspacePopupIsOpen: (state) => () => {
      return state.newWorkspacePopupIsOpen
    },
    newBoardPopupIsOpen: (state) => () => {
      return state.newBoardPopupIsOpen
    },
    newLaneFormIsOpen: (state) => () => {
      return state.newLaneFormIsOpen
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
    async storeWorkspace({commit}, {name}) {
      commit('incrementApiCallsQueue')
      const response = await axios.storeWorkspace({name})
      if (response.status == 201) {
        commit('storeWorkspace', {workspace: response.data.data})
        commit('closeNewWorkspacePopup')
      }
      commit('decrementApiCallsQueue')
    },
    async storeBoard ({commit}, {name, workspace_id}) {
      commit('incrementApiCallsQueue')
      const response = await axios.storeBoard({name, workspace_id})
      if (response.status == 201) {
        commit('storeBoard', {board: response.data.data})
        commit('closeNewLaneForm')
      }
      commit('decrementApiCallsQueue')
    },
    async storeLane ({commit}, {name, board_id}) {
      commit('incrementApiCallsQueue')
      const response = await axios.storeLane({name, board_id})
      if (response.status == 201) {
        commit('storeLane', {lane: response.data.data})
        commit('closeNewBoardPopup')
      }
      commit('decrementApiCallsQueue')
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
    },

  },

  mutations: {
    removeInitialLoading (state) {
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
    getWorkspaces (state, {workspaces}) {
      state.workspaces = workspaces
    },
    resetCurrents (state) {
      state.currentWorkspace = null
      state.currentBoard = null
    },
    storeWorkspace (state, {workspace}) {
      state.workspaces.push(workspace)
    },
    storeBoard (state, {board}) {
      state.currentWorkspace.boards.push(board)
    },
    storeLane (state, {lane}) {
      state.currentBoard.lanes.push(lane)
    },
    setCurrentWorkspace (state, { workspace }) {
      state.currentWorkspace = workspace
    },
    setCurrentBoard (state, {board}) {
      state.currentBoard = board
    },
    openNewBoardPopup (state) {
      state.newBoardPopupIsOpen = true
    },
    closeNewBoardPopup (state) {
      state.newBoardPopupIsOpen = false
    },
    openNewWorkspacePopup (state) {
      state.newWorkspacePopupIsOpen = true
    },
    closeNewWorkspacePopup (state) {
      state.newWorkspacePopupIsOpen = false
    },
    openSidebar (state) {
      state.sidebarIsOpen = true
    },
    closeSidebar (state) {
      state.sidebarIsOpen = false
    },
    openNewLaneForm (state) {
      state.newLaneFormIsOpen = true
    },
    closeNewLaneForm (state) {
      state.newLaneFormIsOpen = false
    },
  }
})

export default todoStore