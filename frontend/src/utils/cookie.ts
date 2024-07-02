/**
 * @param {String} name
 * @param {String} value
 * @param {Record<string, string>} options
 */
export function setCookie(name: string, value: string, options: any = {}) {
  options = {
    path: '/',
    ...options,
  };

  if (options.expires instanceof Date) {
    options.expires = options.expires.toUTCString()
  }

  let updatedCookie = encodeURIComponent(name) + '=' + encodeURIComponent(value)

  for (const optionKey in options) {
    updatedCookie += '; ' + optionKey
    const optionValue = options[optionKey]
    if (optionValue !== true) {
      updatedCookie += '=' + optionValue
    }
  }

  document.cookie = updatedCookie
}

/**
 * @param {String} name
 *
 * @return {String|null}
 */
export function getCookie(name: string): string | null {
  const matches = document.cookie.match(
    // Match if cookie name is decoded correctly in cookie
    new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[]\\\/+^])/g, '\\$1') + '=([^;]*)'),
  );
  return matches ? decodeURIComponent(matches[1]) : null
}

/**
 * @param {String} name
 */
export function deleteCookie(name: string) {
  setCookie(name, '', { expires: new Date(0) })
}
