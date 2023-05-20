<template>
    <div class="tw-flex tw-flex-col tw-items-center tw-w-full">
        <h2>Upcoming {{ type }}</h2>

        <div class="tw-flex tw-justify-center">
            <div v-if="loading">Loading...</div>

            <div v-else class="tw-flex tw-justify-center tw-gap-2 tw-flex-wrap">
                <div v-for="(item, index) in items" :key="index"
                     class="tw-flex tw-justify-center tw-items-end tw-p-5 tw-gap-2 tw-w-[13.75rem] tw-h-[20.625rem] tw-rounded-xl"
                     :style="`background:url('` + getUrl(item.poster_path) + `');`"
                     style="background-size:100%">

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
        loading: false
    }),
    created() {
        this.getItems();
    },
    methods: {
        getItems() {
            this.loading = true;

            axios.get(this.apiInfos.url, {params: this.apiInfos.params}).then((r) => {
                this.items = r.data.results;
            }).catch((error) => {
                console.error(error);
            }).finally(() => {
                this.loading = false;
            });
        },
        getUrl(posterPath) {
            return this.apiInfos.urlPosters + posterPath;
        },
        frenchizeDate(date) {
            return new moment(date).format('DD/MM/YYYY');
        }
    }
})
</script>

<style scoped>

</style>
