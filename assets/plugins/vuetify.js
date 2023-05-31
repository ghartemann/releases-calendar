import '@mdi/font/css/materialdesignicons.css';
import {createVuetify} from 'vuetify';
import {aliases, mdi} from 'vuetify/iconsets/mdi';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';

import { VSkeletonLoader} from "vuetify/labs/components";

const vuetify = createVuetify({
    components: {
        ...components,
        VSkeletonLoader
    },
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
})

export default vuetify;
