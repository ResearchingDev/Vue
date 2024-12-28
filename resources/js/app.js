import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import '@/assets/scss/app.scss';

import { getIconPath } from './heplers/iconHelper';

// Import third-party plugins
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueAnimateOnScroll from 'vue3-animate-onscroll';
import Lightbox from 'vue-easy-lightbox';
import { VueMasonryPlugin } from 'vue-masonry';
import vue3StarRatings from 'vue3-star-ratings';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import PerfectScrollbar from 'vue3-perfect-scrollbar';
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css';
import DropZone from 'dropzone-vue';
import 'bootstrap/dist/js/bootstrap.bundle';

// Import i18n localization
import { createI18n } from 'vue-i18n';
import English from './locales/en.json';
import Français from './locales/fr.json';
import Português from './locales/pt.json';
import Español from './locales/es.json';
import Deutsch from './locales/de.json';
import 简体中文 from './locales/cn.json';
import لعربية from './locales/ae.json';

// Import components
import VueFeather from 'vue-feather';
import Breadcrumbs from './components/bread_crumbs.vue';
import VueApexCharts from 'vue3-apexcharts';
import settingPage from './components/settingPage.vue';
import bcard from './components/b-card.vue';

// Import additional components and styles
import Datepicker from 'vue3-datepicker';
import SimpleTypeahead from 'vue3-simple-typeahead';
import 'vue3-simple-typeahead/dist/vue3-simple-typeahead.css';
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.css';
import VueNumber from 'vue-number-animation';

// i18n configuration
import { defaultLocale, localeOptions } from './constants/config';
const locale = (localStorage.getItem('currentLanguage') && localeOptions.some(x => x.id === localStorage.getItem('currentLanguage')))
  ? localStorage.getItem('currentLanguage')
  : defaultLocale;

const i18n = createI18n({
  locale,
  messages: {
    English,
    Français,
    Português,
    Español,
    Deutsch,
    简体中文,
    لعربية,
  },
});

// Create and configure the Vue app
const app = createApp(App);

app.config.globalProperties.$getIconPath = getIconPath;

app
  .use(router)
  .use(store)
  .use(Toast)
  .use(Vue3Toasity)
  .use(VueSweetalert2)
  .use(VueAnimateOnScroll)
  .use(Lightbox)
  .use(VueMasonryPlugin)
  .use(i18n)
  .use(PerfectScrollbar)
  .use(DropZone)
  .component('vue3-star-ratings', vue3StarRatings)
  .component('EasyDataTable', Vue3EasyDataTable)
  .component('apexchart', VueApexCharts)
  .component('multiselect', Multiselect)
  .component('Breadcrumbs', Breadcrumbs)
  .component(VueFeather.name, VueFeather)
  .component('settingPage', settingPage)
  .component('b-card', bcard)
  .component('Datepicker', Datepicker)
  .mount('#app');
