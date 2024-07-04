import api from '@/services/api'

const files = {
  /**
   * @param {String} uuid
   *
   * @return {Promise<{String}>}
   */
  getUrl(uuid: string): Promise<String> {
    return api
      .get(`/file/${uuid}`, {}, {}, { responseType: 'blob' })
      .then((r) => URL.createObjectURL(r))
  }
}

export default files
