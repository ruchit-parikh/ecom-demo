import { computed, ref } from 'vue'
import type { Ref } from 'vue'

export interface FormInput {
  name: string
  placeholder?: string
  validate?: Array<(v: string) => boolean | string>
}

export function useFormInput(props: FormInput, defaults: Record<string, any> = {}) {
  const fieldName: string = props.name
  const placeholderText: string | undefined = props.placeholder

  const messages: Ref<string[]> = ref([])

  /**
   * @return {Array<(v: string) => boolean|string>}
   */
  const rules = computed(() => {
    let res = props.validate ? [...props.validate] : []
    res = defaults.rules ? [...res, ...defaults.rules] : res

    return res
  })

  /**
   * @param {any} value
   *
   * @return {Boolean}
   */
  const isValid = (value: any): Boolean => {
    for (const rule of rules.value) {
      if (rule(value) !== true) {
        return false
      }
    }

    return true
  }

  return { fieldName, messages, placeholderText, rules, isValid }
}
