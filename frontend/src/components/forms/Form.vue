<template>
  <VForm @submit.prevent="doSubmit">
    <slot></slot>
  </VForm>
</template>

<script lang="ts">
import { defineComponent, PropType } from 'vue';
import { VForm } from "vuetify/components";

export default defineComponent({
  name: 'Form',
  components: {
    VForm,
  },
  props: {
    action: {
      type: Function as PropType<(e: Event) => Promise<any>>,
      required: true
    },
    inputs: {
      type: Object as PropType<Array<any>>,
      required: true
    }
  },
  setup(props) {
    const doSubmit = (e: Event) => {
      e.preventDefault();

      return props.action(e)
          .catch(err => {
            let errors: {};

            if (err.response?.data?.errors) {
              for (const i in err.response.data.errors) {
                if (Object.prototype.hasOwnProperty.call(err.response.data.errors, i)) {
                 errors[i] = err.response.data.errors[i];
                }
              }

              if (errors) {
                for (let input of props.inputs) {
                  if (typeof input.fieldName && errors.hasOwnProperty(input.fieldName)) {
                    input.messages = errors[input.fieldName]
                  }
                }
              }
            }

            return err;
          })
    };

    return { doSubmit };
  }
});
</script>
