import axios, {AxiosResponse} from 'axios'
import ApiError from '@/services/errors/apiError'

const client = axios.create({
  baseURL: import.meta.env.VUE_APP_API_URL,
  headers: {
    'Content-Type': 'application/json'
  }
})

const api = {
  /**
   * @param {String} path
   * @param {Record<string, string>} query
   * @param {Record<string, string>} headers
   *
   * @return {Promise<any>}
   */
  get(
    path: string,
    query: Record<string, string> = {},
    headers: Record<string, string> = {}
  ): Promise<any> {
    return client.get(path, { params: query }).catch((err) => {
      throw new ApiError(err.response.status, err.response.data.error)
    })
  },

  /**
   * @param {String} path
   * @param {Record<string, any>} body
   * @param {Record<string, string>} headers
   *
   * @return {Promise<any>}
   */
  post(
    path: string,
    body: Record<string, any> = {},
    headers: Record<string, string> = {}
  ): Promise<any> {
    return client.post(path, body).catch((err) => {
      throw new ApiError(err.response.status, err.response.data.error)
    })
  },

  /**
   * @param {String} path
   * @param {Record<string, any>} body
   * @param {Record<string, string>} headers
   *
   * @return {Promise<any>}
   */
  put(
    path: string,
    body: Record<string, any> = {},
    headers: Record<string, string> = {}
  ): Promise<any> {
    return client.put(path, body).catch((err) => {
      throw new ApiError(err.response.status, err.response.data.error)
    })
  }
}

export default api
