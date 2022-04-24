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
    currentLanes: null,

    initialLoading: true,
    apiCallsQueue: 0,
    loading: false,
    
    newBoardPopupIsOpen: false,
    newMemberPopupIsOpen: false,
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
    currentLanes: (state) => () => {
      return state.currentLanes
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
      if (currentBoard) {
        commit('setCurrentBoard', {board: currentBoard})
        const rawLanesArray = currentBoard.lanes ?? []
        const sortedLanesArray = []
        let last_id = null
        for (let i = 0; i < rawLanesArray.length; i++) {
          let lane = rawLanesArray.find(lane => lane.previous_id === last_id)
          last_id = lane.id
          sortedLanesArray.push(lane)
          if (!lane.next_id) break
        }
        commit('setCurrentLanes', {lanes: sortedLanesArray})
      }
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
        commit('closeNewBoardPopup')
      }
      commit('decrementApiCallsQueue')
    },
    async storeLane ({commit}, {name, board_id}) {
      commit('incrementApiCallsQueue')
      const response = await axios.storeLane({name, board_id})
      if (response.status == 201) {
        commit('storeLane', {lane: response.data.data})
      }
      commit('decrementApiCallsQueue')
    },
    async editLane ({commit, state}, {lane_id, name}) {
      commit('incrementApiCallsQueue')
      const response = await axios.editLane(lane_id, {name})
      if (response.status == 200) {
        const index = state.currentLanes.findIndex((element) => element.id == lane_id)
        const currentLanes = state.currentLanes
        currentLanes.splice(index, 1, response.data.data)
      }
      commit('decrementApiCallsQueue')
    },
    async deleteLane ({commit, state}, {lane_id}) {
      commit('incrementApiCallsQueue')
      const response = await axios.deleteLane(lane_id)
      if (response.status = 200) {
        const index = state.currentLanes.findIndex((element) => element.id == lane_id)
        const currentLanes = state.currentLanes
        currentLanes.splice(index, 1)
        commit('setCurrentLanes', {lanes: currentLanes})
      }
      commit('decrementApiCallsQueue')
    },
    async moveLane ({commit}, {lane_id, previous_id}) {
      commit('incrementApiCallsQueue')
      await axios.moveLane(lane_id, {previous_id})
      commit('decrementApiCallsQueue')
    },
    async storeCard ({commit}, {lane, name, description}) {
      commit('incrementApiCallsQueue')
      const response = await axios.storeCard({name, description, lane_id: lane.id})
      if (response.status == 201) {
        const createdCard = response.data.data
        lane.cards.push(createdCard)
      }
      commit('decrementApiCallsQueue')
    },
    async moveCard ({commit}, {card_id, previous_id}) {
      commit('incrementApiCallsQueue')
      await axios.moveCard(card_id, {previous_id})
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
      state.currentBoard = board
    },
    storeLane (state, {lane}) {
      state.currentBoard.lanes.push(lane)
      state.currentLanes.push(lane)
    },
    setCurrentWorkspace (state, { workspace }) {
      state.currentWorkspace = workspace
    },
    setCurrentBoard (state, {board}) {
      state.currentBoard = board
    },
    setCurrentLanes (state, {lanes}) {
      state.currentLanes = lanes
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