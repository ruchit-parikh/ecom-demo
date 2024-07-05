import { createVuetify } from 'vuetify'
import { mount } from '@vue/test-utils'

export function useVuetifyMount(component: any) {
  const vuetify = createVuetify()

  /**
   * @param {Record<any, any>} options
   *
   * @return {any}
   */
  const mountFunction = (options = {}) => {
    return mount(component, {
      global: {
        plugins: [vuetify]
      },
      ...options
    })
  }

  return { mountFunction }
}
