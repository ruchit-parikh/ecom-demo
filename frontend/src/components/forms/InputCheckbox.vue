<template>
  <VCheckbox
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
import { VCheckbox } from 'vuetify/components'
import { useFormInput } from '@/components/forms/composiables/FormInput'

export default defineComponent({
  name: 'InputCheckbox',
  components: {
    VCheckbox
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
    const value: Ref<UnwrapRef<Boolean>> = ref(false)
    const params = useFormInput(props)

    return { ...params, value }
  }
})
</script>
