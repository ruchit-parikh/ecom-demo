<template>
  <VForm @submit.prevent="doSubmit">
    <slot></slot>
  </VForm>
</template>

<script lang="ts">
import type { PropType } from 'vue'
import { defineComponent } from 'vue'
import { VForm } from 'vuetify/components'

export default defineComponent({
  name: 'ActionForm',
  components: {
    VForm
  },
  props: {
    action: {
      type: Function as PropType<(e: Event) => Promise<any>>,
      required: true
    },
    inputs: {
      type: Object as PropType<Record<string, any>>,
      required: true
    }
  },
  setup(props) {
    const doSubmit = (e: Event) => {
      e.preventDefault()

      for (const input of Object.values(props.inputs)) {
        if (!input.isValid(input.value)) {
          throw new Error('Input field ' + input.fieldName + ' is invalid')
        }
      }

      return props.action(e).catch((err) => {
        let errors: Record<string, any> = {}

        if (err.response?.data?.errors) {
          for (const i in err.response.data.errors) {
            if (Object.prototype.hasOwnProperty.call(err.response.data.errors, i)) {
              errors[i] = err.response.data.errors[i]
            }
          }

          if (errors) {
            for (const input of Object.values(props.inputs)) {
              if (
                typeof input.fieldName &&
                Object.prototype.hasOwnProperty.call(errors, input.fieldName)
              ) {
                input.messages = errors[input.fieldName]
              }
            }
          }
        }

        return err
      })
    }

    return { doSubmit }
  }
})
</script>
