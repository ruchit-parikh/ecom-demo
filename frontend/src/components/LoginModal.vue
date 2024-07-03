<template>
  <Dialog ref="dialog">
    <Form :action="login" :inputs="this.$refs">
      <InputEmail placeholder="Email" name="email" ref="email"></InputEmail>

      <InputPassword placeholder="Password" name="password" ref="password"></InputPassword>

      <VBtn :disalbed="loading" size="small" height="40" width="100%" color="primary" :loading="loading" type="submit">Login</VBtn>
    </Form>
  </Dialog>
</template>

<script lang="ts">
import {defineComponent, ref} from 'vue';
import Dialog from '@/components/Dialog.vue';
import eventBus from '@/plugins/eventBus';
import auth from "@/services/auth";
import Form from "@/components/forms/Form.vue";
import InputPassword from "@/components/forms/InputPassword.vue";
import InputEmail from "@/components/forms/InputEmail.vue";

export default defineComponent({
  name: 'LoginModal',
  components: {
    InputEmail,
    InputPassword,
    Form,
    Dialog,
  },
  setup() {
    const dialog = ref<InstanceType<typeof Dialog> | null>(null);
    const email = ref('');
    const password = ref('');
    const loading = ref(false);

    /**
     * @return {Promise<any>}
     */
    const login = (): Promise<any> => {
      loading.value = true

      return auth.login(email.value.value, password.value.value)
          .then(r => {
            setTimeout(() => {
              dialog.value.closeDialog();
            }, 2000);
          })
          .finally(() => {
            loading.value = false
          })
    };

    eventBus.on('user-unauthorized', () => {
      dialog.value.openDialog();
    });

    return {
      loading,
      login,
      email,
      password,
      dialog
    };
  }
});
</script>
