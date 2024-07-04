import api from '@/services/api'

const files = {
  /**
   * @param {string} uuid
   *
   * @return {Promise<{string}>}
   */
  getUrl(uuid: string): Promise<string> {
    return api
      .get(`/file/${uuid}`, {}, {}, { responseType: 'blob' })
      .then((r) => URL.createObjectURL(r))
  }
}

export default files
