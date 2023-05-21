// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import { createApp } from 'vue';
import App from '@pages/App.vue';
import router from "./js/router";
import vuetify from './plugins/vuetify';

createApp(App)
    .use(router)
    .use(vuetify)
    .mount('#app');
