<template>
  <VTextField
    :label="placeholderText"
    type="password"
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
import { useFormInput } from '@/components/forms/composables/FormInput'

export default defineComponent({
  name: 'InputPassword',
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
    const defaults = useFormInput(props)
    const value: Ref<UnwrapRef<string>> = ref('')

    return { ...defaults, value }
  }
})
</script>
