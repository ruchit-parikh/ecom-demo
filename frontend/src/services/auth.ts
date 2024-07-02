import api from "@/services/api"
import { deleteCookie, getCookie, setCookie } from "@/utils/cookie";
import eventBus from "@/plugins/eventBus";
import ApiError from "@/services/errors/apiError";

/**
 * @param {ApiError} err
 */
const triggerError = (err: ApiError) => {
  if (err.isUnAuthorized()) {
    eventBus.emit('user-unauthorized');
  }

  if (err.isForbidden(0)) {
    eventBus.emit('user-forbidden');
  }
}

const auth = {
  /**
   * @param {String} email
   * @param {String} password
   *
   * @return {Promise<{ access_token: string; refresh_token: string }>}
   */
  login(email: string, password: string): Promise<{ access_token: string; refresh_token: string }> {
    return api.post('/user/login', {email: email, password: password})
      .then((r) => {
        const { access_token, refresh_token } = r.data;

        setCookie('access_token', access_token, { secure: true, sameSite: 'strict' });
        setCookie('refresh_token', refresh_token, { secure: true, sameSite: 'strict' });

        return { access_token, refresh_token };
      })
  },

  /**
   * @return {Promise<any>}
   */
  logout(): Promise<any> {
    return this.post('/user/logout')
      .then(r => {
        deleteCookie('access_token')
        deleteCookie('refresh_token')

        eventBus.emit('user-unauthorized');

        return r
      })
  },

  /**
   * @return {Promise<any>>}
   */
  getCurUser(): Promise<any> {
    return this.get('/user')
      .then (r => r.data)
  },

  /**
   * @param {String} path
   * @param {Record<string, string>} query
   *
   * @return {Promise<any>}
   */
  get (path: string, query: Record<string, string> = {}): Promise<any> {
    return api.get(path, query, {'Authorization': 'Bearer ' + getCookie('access_token')})
      .catch(err => {
        triggerError(err)

        return err;
      })
  },

  /**
   * @param {String} path
   * @param {Record<string, any>} body
   *
   * @return {Promise<any>}
   */
  post (path: string, body: Record<string, any> = {}): Promise<any> {
    return api.post(path, body, {'Authorization': 'Bearer ' + getCookie('access_token')})
      .catch(err => {
        triggerError(err)

        return err;
      })
  },

  /**
   * @param {String} path
   * @param {Record<string, any>} body
   *
   * @return {Promise<any>}
   */
  put (path: string, body: Record<string, any> = {}): Promise<any> {
    return api.put(path, body, {'Authorization': 'Bearer ' + getCookie('access_token')})
      .catch(err => {
        triggerError(err)

        return err;
      })
  }
}

export default auth
