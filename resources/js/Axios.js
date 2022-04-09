import axios from "axios";

export default class TodoAxios {

  instance = axios.create({
    baseURL: process.env.APP_URL,
    headers: this.setHeaders()
  })

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

  get(path) {
    return this.instance.get(path)
  }

  post(path, data) {
    return this.instance.post(path, data)
  }

}