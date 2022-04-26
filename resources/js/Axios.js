import axios from "axios";

export default class TodoAxios {

  instance = axios.create({
    baseURL: process.env.APP_URL,
    headers: this.setHeaders()
  })

  routes = {
    user: {
      show: '/user',
      updateImage: (id) => {return '/user/' + id + '/updateImage'}
    },
    workspace: {
      index: '/workspace',
      store: '/workspace',
      show: (id) => { return '/workspace/' + id },
      addMember: (id) => {return '/workspace/' + id + '/addMember'},
      removeMember: (id) => {return '/workspace/' + id + '/removeMember'},
      delete: (id) => {return '/workspace/' + id},
    },
    board: {
      index: '/board',
      store: '/board',
      show: (id) => { return '/board/' + id },
    },
    lane: {
      store: '/lane',
      edit: (id) => {return '/lane/' + id},
      move: (id) => {return '/lane/' + id + '/move'},
      delete: (id) => {return '/lane/' + id},
    },
    card: {
      store: '/card',
      move: (id) => {return '/card/' + id + '/move'},
    }
  }

  setHeaders() {
    const headers = {"X-Requested-With": "XMLHttpRequest"}
    const cookies = document.cookie.split(';')
    cookies.map((cookie) => {
        const splitted = cookie.split('=')
        const key = splitted[0]
        const value = splitted[1]
        Object.defineProperty(headers, key, {value})
    })
    return headers
  }

  async get(path) {
    return await this.instance.get(path)
  }

  async post(path, data) {
    return await this.instance.post(path, data)
  }

  async put(path, data) {
    return await this.instance.put(path, data)
  }

  async delete(path) {
    return await this.instance.delete(path)
  }

  async getUser() {
    const response = await this.get(this.routes.user.show)
    return response
  }

  async updateUserImage(id, data) {
    const response = await this.post(this.routes.user.updateImage(id), data)
    return response
  }
  
  async getUserWorkspaces() {
    const response = await this.get(this.routes.workspace.index)
    return response
  }

  async getWorkspace(id) {
    const response = await this.get(this.routes.workspace.show(id))
    return response
  }

  async addMember(id, data) {
    const response = await this.put(this.routes.workspace.addMember(id), data)
    return response
  }

  async removeMember(id, data) {
    const response = await this.put(this.routes.workspace.removeMember(id), data)
    return response
  }

  async deleteWorkspace(id) {
    const response = await this.delete(this.routes.workspace.delete(id))
    return response
  }

  async getBoard(id) {
    const response = await this.get(this.routes.board.show(id))
    return response
  }
  
  async storeWorkspace(data) {
    const response = await this.post(this.routes.workspace.store, data)
    return response
  }

  async storeBoard(data) {
    const response = await this.post(this.routes.board.store, data)
    return response
  }

  async storeLane(data) {
    const response = await this.post(this.routes.lane.store, data)
    return response
  }

  async editLane(id, data) {
    const response = await this.put(this.routes.lane.edit(id), data)
    return response
  }

  async deleteLane(id) {
    const response = await this.delete(this.routes.lane.delete(id))
    return response
  }

  async moveLane(id, data) {
    const response = await this.put(this.routes.lane.move(id), data)
    return response
  }

  async storeCard(data) {
    const response = await this.post(this.routes.card.store, data)
    return response
  }

  async moveCard(id, data) {
    const response = await this.put(this.routes.card.move(id), data)
    return response
  }
}