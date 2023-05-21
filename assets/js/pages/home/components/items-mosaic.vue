<template>
    <div class="tw-flex tw-flex-col tw-items-center tw-w-full tw-my-8">
        <h2 class="tw-text-5xl tw-my-5">Upcoming {{ type }}</h2>

        <div class="tw-flex tw-flex-col tw-justify-center tw-items-center">
            <div class="tw-text-white">params <v-icon color="white">mdi-check</v-icon></div>

            <div v-if="loading">Loading...</div>

            <div v-else class="tw-grid tw-grid-cols-5 tw-gap-4">
                <div v-for="(item, index) in items" :key="index"
                     class="tw-col-span-1 tw-w-[13.75rem] tw-h-[20.625rem] tw-rounded-xl !tw-bg-cover !tw-bg-center"
                     :style="`background:url('` + getUrl(item[apiInfos.specificInfos.posterParamName]) + `');`"
                    @mouseover="hovered[index] = true" @mouseout="hovered[index] = false">

                    <div v-show="hovered[index] === true"
                         class="tw-flex tw-flex-col tw-justify-center tw-items-center tw-gap-2 tw-p-5 text-center tw-bg-white tw-bg-opacity-60 tw-h-full tw-w-full tw-rounded-xl">
                        <div class="tw-font-black">{{ item[apiInfos.specificInfos.titleParamName] }}</div>
                        <div>{{ frenchizeDate(item[apiInfos.specificInfos.dateParamName]) }}</div>
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
        hovered: [],
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

                        this.hovered = [...new Array(this.items.length)].map(x => false);
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

                this.hovered = [...new Array(this.items.length)].map(x => false);

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
        sortByDate(items, date) {
            return items.sort((a,b) => {
                return new Date(a[date]) - new Date(b[date]);
            });
        }
    }
})
</script>

<style scoped>

</style>
