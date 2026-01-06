import './bootstrap';
// Build 2026-01-05 Refinement Update

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';

import { useAuthStore } from './stores/auth';
import { useSettingsStore } from './stores/settings';

// PrimeVue Setup with Lara Theme
import PrimeVue from 'primevue/config';
import ConfirmationService from 'primevue/confirmationservice';
import Lara from '@primevue/themes/lara';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Tooltip from 'primevue/tooltip';
import ScrollTop from 'primevue/scrolltop';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.use(ConfirmationService);
app.use(ToastService);
app.use(PrimeVue, {
    theme: {
        preset: Lara
    }
});

app.component('DataTable', DataTable);
app.component('Column', Column);
app.component('Dropdown', Dropdown);
app.component('InputText', InputText);
app.component('IconField', IconField);
app.component('InputIcon', InputIcon);
app.component('Button', Button);
app.component('ScrollTop', ScrollTop);
app.component('Toast', Toast);
app.directive('tooltip', Tooltip);

// Initialize auth (restore authentication from localStorage)
const authStore = useAuthStore();
authStore.initialize();

// Load font settings
const settingsStore = useSettingsStore();
settingsStore.loadSettings();

app.mount('#app');
