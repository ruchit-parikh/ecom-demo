import auth from '@/services/auth'

const orders = {
  /**
   * @param {number} page
   * @param {number} limit
   * @param {string} sortBy
   * @param {Boolean} desc
   *
   * @return {Promise<{any}>}
   */
  getUserOrders(
    page: number = 1,
    limit: number = 10,
    sortBy: string = 'created_at',
    desc: Boolean = false
  ): Promise<any> {
    return auth.get(`/user/orders`, { page: page, limit: limit, sortBy: sortBy, desc: desc })
  }
}

export default orders
