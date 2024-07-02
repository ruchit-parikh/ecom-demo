import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import { VApp, VMain, VAppBar, VBtn, VCard, VContainer, VRow, VCol, VProgressLinear } from "vuetify/components";
import { Ripple } from 'vuetify/directives'

export default createVuetify({
  theme: {
    themes: {
      light: {
        colors: {
          primary: '#4ec690',
          secondary: '#FFFFFF',
        },
      },
    }
  },
  components: {
    VApp,
    VMain,
    VAppBar,
    VBtn,
    VCard,
    VContainer,
    VRow,
    VCol,
    VProgressLinear
  },
  directives: {
    Ripple
  },
})
