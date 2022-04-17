import axios from "axios";

export default class TodoAxios {

  instance = axios.create({
    baseURL: process.env.APP_URL,
    headers: this.setHeaders()
  })

  routes = {
    workspace: {
      index: '/workspace',
      store: '/workspace',
      show: (id) => { return '/workspace/' + id },
    },
    board: {
      index: '/board',
      store: '/board',
      show: (id) => { return '/board/' + id },
    },
    lane: {
      store: '/lane',
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

  async getUserWorkspaces() {
    const response = await this.get(this.routes.workspace.index)
    return response
  }

  async getWorkspace(id) {
    const response = await this.get(this.routes.workspace.show(id))
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
}