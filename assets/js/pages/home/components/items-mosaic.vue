<template>
    <div class="tw-flex tw-flex-col tw-items-center tw-w-full">
        <h2>Upcoming {{ type }}</h2>

        <div class="tw-flex tw-justify-center">
            <div v-if="loading">Loading...</div>

            <div v-else class="tw-flex tw-justify-center tw-gap-2 tw-flex-wrap">
                <div v-for="(item, index) in items" :key="index"
                     class="tw-flex tw-justify-center tw-items-end tw-p-5 tw-gap-2 tw-w-[13.75rem] tw-h-[20.625rem] tw-rounded-xl !tw-bg-cover hover:tw-opacity-60"
                     :style="`background:url('` + getUrl(item.poster_path) + `');`">

                    <div class="tw-absolute tw-text-white text-center">
                        <div>{{ frenchizeDate(item.release_date) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import axios from "axios";
import moment from "moment/moment";

export default defineComponent({
    name: "items-mosaic",
    props: {
        type: {
            type: String,
            required: true
        },
        apiInfos: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        items: [],
        source: '',
        loading: false
    }),
    created() {
        this.getItemsFromDb();
    },
    methods: {
        getItemsFromDb() {
            this.loading = true;
            let itemsTooRecent = false;

            axios.get('/api/get-upcoming-' + this.type).then((r) => {
                if (r.data !== null) {
                    itemsTooRecent = new moment().diff(new moment(r.data.createdAt), 'hours') < 24;

                    if (Object.keys(r.data).length === 0 || itemsTooRecent === false) {
                        this.getItemsFromApi();
                    } else {
                        this.source = 'db';

                        this.items = r.data.content;
                    }
                }
            }).catch((error) => {
                console.error(error);
            }).finally(() => {
                this.loading = false;
            });
        },
        getItemsFromApi() {
            this.loading = true;

            axios.get(this.apiInfos.specificInfos.url, {params: this.apiInfos.params}).then((r) => {
                this.source = 'api';

                this.items = r.data.results;
                this.sortByDate(this.items, this.apiInfos.specificInfos.dateParamName);

                this.saveItemsToDb();
            }).catch((error) => {
                console.error(error);
            }).finally(() => {
                this.loading = false;
            });
        },
        saveItemsToDb() {
            axios.post('/api/save-upcoming-' + this.type, {items: this.items}).then((r) => {

            }).catch((error) => {
                console.error(error);
            });
        },
        getUrl(posterPath) {
            return this.apiInfos.specificInfos.urlPosters + posterPath;
        },
        frenchizeDate(date) {
            return new moment(date).format('DD/MM/YYYY');
        },
        sortByDate(items, parameter) {
            return items.sort((a,b) => {
                return new Date(a[parameter]) - new Date(b[parameter]);
            });
        }
    }
})
</script>

<style scoped>

</style>
