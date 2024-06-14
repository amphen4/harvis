//import 'dotenv/config'
import { createApp } from 'vue';
import App from './App.vue';
import { router } from './router';
import store from './stores';
console.log(store);
import vuetify from './plugins/vuetify';
import '@/scss/style.scss';
//import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'
import { PerfectScrollbarPlugin } from 'vue3-perfect-scrollbar';
import VueTablerIcons from 'vue-tabler-icons';
import VueApexCharts from 'vue3-apexcharts';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';

// google-fonts
import '@fontsource/public-sans/400.css';
import '@fontsource/public-sans/500.css';
import '@fontsource/public-sans/600.css';
import '@fontsource/public-sans/700.css';

//Mock Api data
import { fakeBackend } from '@/utils/helpers/fake-backend';

//i18
import { createI18n } from 'vue-i18n';
import messages from '@/utils/locales/messages';

const i18n = createI18n({
  locale: 'en',
  messages: messages,
  silentTranslationWarn: true,
  silentFallbackWarn: true
});

const app = createApp(App);
fakeBackend();
app.use(router);
app.use(store);
app.use(PerfectScrollbarPlugin);
app.use(VueTablerIcons);
app.use(Antd);
app.use(i18n);
app.use(VueApexCharts);
app.use(vuetify).mount('#app');
