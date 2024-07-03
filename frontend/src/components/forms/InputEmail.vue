<template>
  <VTextField :label="placeholder" variant="outlined" :name="fieldName" v-model="value" :error="messages.length > 0" :error-messages="messages[0]" />
</template>

<script lang="ts">
import {defineComponent, ref} from 'vue';
import {VTextField} from "vuetify/components";

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
      required: true
    }
  },
  setup(props) {
    const fieldName = props.name;
    const value = ref('');
    const placeholder = props.placeholder;
    const messages = ref([]);

    return {fieldName, value, messages, placeholder};
  }
});
</script>
