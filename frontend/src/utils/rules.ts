/**
 * @param {any} v
 *
 * @return {Boolean}
 */
export const required = (v: any) => !!v || 'This field is required'

/**
 * @param {String} v
 *
 * @return {Boolean}
 */
export const email = (v: string) => /.+@.+\..+/.test(v) || 'E-mail must be valid'
