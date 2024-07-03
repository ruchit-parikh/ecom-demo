<template>
  <VTextField :label="placeholder" variant="outlined" :name="fieldName" v-model="value" :error="messages.length > 0" :error-messages="messages[0]" />
</template>

<script lang="ts">
import {defineComponent, ref} from 'vue';
import {VTextField} from "vuetify/components";

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
    }
  },
  setup(props) {
    const fieldName = props.name;
    const placeholder = props.placeholder;
    const value = ref('');
    const messages = ref([]);

    return {fieldName, value, messages, placeholder};
  }
});
</script>
