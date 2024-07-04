import api from '@/services/api'
import { deleteCookie, getCookie, setCookie } from '@/utils/cookie'
import eventBus from '@/plugins/eventBus'
import type ApiError from '@/services/errors/apiError'

/**
 * @param {ApiError} err
 */
const triggerError = (err: ApiError) => {
  if (err.isUnAuthorized()) {
    eventBus.emit('user-unauthorized')
  }

  if (err.isForbidden()) {
    eventBus.emit('user-forbidden')
  }
}

const auth = {
  /**
   * @param {String} email
   * @param {String} password
   * @param {Boolean} remember
   *
   * @return {Promise<{ token: string }>}
   */
  login(email: string, password: string, remember: Boolean = false): Promise<{ token: string }> {
    return api.post('/user/login', { email: email, password: password }).then((r) => {
      const { token } = r.data

      if (remember) {
        const expiry = new Date()
        expiry.setHours(expiry.getHours() + 24)

        setCookie('token', token, { secure: true, sameSite: 'strict', expires: expiry })
      } else {
        setCookie('token', token, { secure: true, sameSite: 'strict' })
      }

      eventBus.emit('user-loggedin')

      return { token }
    })
  },

  /**
   * @return {Promise<any>}
   */
  logout(): Promise<any> {
    // TODO: This shouldn't be a get request as we are not getting any thing from server and rather a put/post
    return this.get('/user/logout').then((r) => {
      deleteCookie('token')

      eventBus.emit('user-loggedout')

      return r
    })
  },

  /**
   * @return {Promise<{string, string}>}
   */
  getCurUser(): Promise<any> {
    return this.get('/user').then((r) => r.data)
  },

  /**
   * @param {String} path
   * @param {Record<string, string>} query
   *
   * @return {Promise<any>}
   */
  get(path: string, query: Record<string, string> = {}): Promise<any> {
    return api.get(path, query, { Authorization: 'Bearer ' + getCookie('token') }).catch((err) => {
      triggerError(err)

      return err
    })
  },

  /**
   * @param {String} path
   * @param {Record<string, any>} body
   *
   * @return {Promise<any>}
   */
  post(path: string, body: Record<string, any> = {}): Promise<any> {
    return api.post(path, body, { Authorization: 'Bearer ' + getCookie('token') }).catch((err) => {
      triggerError(err)

      return err
    })
  },

  /**
   * @param {String} path
   * @param {Record<string, any>} body
   *
   * @return {Promise<any>}
   */
  put(path: string, body: Record<string, any> = {}): Promise<any> {
    return api.put(path, body, { Authorization: 'Bearer ' + getCookie('token') }).catch((err) => {
      triggerError(err)

      return err
    })
  }
}

export default auth
