<template>
  <VTextField
    :label="placeholderText"
    variant="outlined"
    :name="fieldName"
    v-model="value"
    :rules="rules"
    :error="messages.length > 0"
    :error-messages="messages[0]"
  />
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import type { UnwrapRef, Ref } from 'vue'
import { VTextField } from 'vuetify/components'
import { useFormInput } from '@/components/forms/composiables/FormInput'
import { email } from '@/utils/rules'

export default defineComponent({
  name: 'InputEmail',
  components: {
    VTextField
  },
  props: {
    name: {
      type: String,
      required: true
    },
    placeholder: {
      type: String,
      required: false
    },
    validate: {
      type: Array as () => Array<(v: string) => boolean | string>,
      required: false
    }
  },
  setup(props) {
    const value: Ref<UnwrapRef<string>> = ref('')
    const params = useFormInput(props, { rules: [email] })

    return { ...params, value }
  }
})
</script>
