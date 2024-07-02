const HTTP_UNAUTHORIZED = 401;
const HTTP_FORBIDDEN = 403;

export default class ApiError extends Error {
  code: number;

  /**
   * @param {Number} code
   * @param {String} message
   */
  constructor(code: number, message: string) {
    super(message);

    this.code = code;
    this.name = "ApiError";
  }

  /**
   * @return {Boolean}
   */
  isUnAuthorized() {
    return this.code === HTTP_UNAUTHORIZED
  }

  /**
   * @return {Boolean}
   */
  isForbidden() {
    return this.code === HTTP_FORBIDDEN
  }
}
