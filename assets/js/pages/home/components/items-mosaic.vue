<template>
    <div class="tw-flex tw-flex-col tw-items-center tw-w-full tw-my-8">
        <div class="tw-flex tw-flex-col tw-justify-center tw-items-center">
            <div class="tw-grid xl:tw-grid-cols-5 lg:tw-grid-cols-4 md:tw-grid-cols-3 sm:tw-grid-cols-2 tw-gap-4 tw-w-full">
                <v-skeleton-loader
                        v-if="loading"
                        v-for="index in 40"
                        :width="220" :height="330" :loading="loading"
                        class="tw-col-span-1 tw-rounded-xl ">
                    {{ index }}
                </v-skeleton-loader>

                <div v-else v-for="(item, index) in items" :key="index"
                     @click="showModal(item)"
                     class="tw-col-span-1 tw-w-[13.75rem] tw-h-[20.625rem] tw-rounded-xl !tw-bg-cover !tw-bg-center tw-relative tw-cursor-pointer"
                     :style="`background:url('` + getUrl(item[apiInfos.specificInfos.posterParamName], 'urlPosters') + `');`"
                     @mouseover="hovered[index] = true" @mouseout="hovered[index] = false">

                    <div class="tw-flex tw-flex-col tw-justify-between tw-items-center tw-gap-2 tw-p-5 text-center tw-text-white hover:tw-bg-black hover:tw-bg-opacity-50 tw-h-full tw-w-full tw-rounded-xl">
                        <div></div>

                        <div v-show="hovered[index]">
                            <div class="tw-font-black">{{ item[apiInfos.specificInfos.titleParamName] }}</div>
                            <div>{{ frenchizeDate(item[apiInfos.specificInfos.dateParamName]) }}</div>
                        </div>

                        <v-btn @click.stop="addOrRemoveFromMyList(item)"
                               v-show="hovered[index] || myList.includes(item)"
                               :color="myList.includes(item) === true ? '#0f2027' : ''"
                               icon flat>
                            <v-icon color="white">
                                {{ myList.includes(item) === false ? 'mdi-plus' : 'mdi-heart' }}
                            </v-icon>
                        </v-btn>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TODO: passer ça en composant-->
    <v-dialog v-model="contentModal"
              transition="dialog-top-transition"
              width="90%">
        <v-card class="!tw-rounded-2xl tw-shadow-2xl tw-h-[80vh] !tw-bg-cover !tw-bg-center"
                :style="`background:url('` + getUrl(activeItemDetails['backdrop_path'], 'urlBackdrops') + `');`">
            <v-card-text v-if="clickedLink === false"
                         class="tw-bg-black tw-bg-opacity-50 tw-grid tw-grid-cols-4 tw-gap-10 !tw-p-8">
                <div class="tw-col-span-1 tw-flex tw-flex-col tw-items-center tw-gap-3">
                    <img :src="getUrl(activeItemDetails['poster_path'], 'urlPosters')"
                         class="tw-rounded-xl tw-shadow-2xl"
                         alt="poster">

                    <div class="tw-text-white tw-underline tw-cursor-pointer" @click="clickedLink = true">Watch
                        trailer
                    </div>
                </div>

                <div class="tw-col-span-3 tw-flex tw-flex-col tw-gap-5">
                    <h4 class="tw-text-5xl tw-font-semibold tw-rounded-xl">
                        {{ activeItemDetails.title }}
                    </h4>

                    <div class="tw-text-white">{{ activeItemDetails.overview }}</div>
                </div>
            </v-card-text>

            <v-card-text v-else class="!tw-p-0 tw-aspect-w-16 tw-aspect-h-9">
                <iframe
                        width="100%"
                        height="100%"
                        :src="'https://www.youtube.com/embed/' + activeItemDetails.videos.results[0].key + '?autoplay=1'"
                        title="YouTube video player"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                </iframe>
            </v-card-text>
        </v-card>
    </v-dialog>
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
        activeItem: {},
        activeItemDetails: {},
        loadingActiveItemDetails: false,
        clickedLink: false,
        source: '',
        hovered: [],
        myList: [],
        loading: false,
        contentModal: false
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
                    itemsTooRecent = new moment().diff(new moment(r.data.createdAt), 'hours') < 12;

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
        getActiveItemDetails() {
            if (this.apiInfos.specificInfos.urlDetails) {
                this.loadingActiveItemDetails = true;

                axios.get(
                    this.apiInfos.specificInfos.urlDetails + this.activeItem.id,
                    {params: this.apiInfos.detailsParams}
                ).then((r) => {
                    this.activeItemDetails = r.data;
                }).catch((error) => {
                    console.error(error);
                }).finally(() => {
                    this.loadingActiveItemDetails = false;
                });
            }
        },
        getUrl(path, type) {
            if (path === null) {
                return 'https://via.placeholder.com/500x750?text=No+poster';
            }

            return this.apiInfos.specificInfos[type] + path;
        },
        frenchizeDate(date) {
            return new moment(date).format('DD/MM/YYYY');
        },
        sortByDate(items, date) {
            return items.sort((a, b) => {
                return new Date(a[date]) - new Date(b[date]);
            });
        },
        addOrRemoveFromMyList(item) {
            if (this.myList.includes(item)) {
                this.myList.splice(this.myList.indexOf(item), 1);
            } else {
                this.myList.push(item);
            }
        },
        showModal(item) {
            this.contentModal = true;
            this.activeItem = item;
        }
    },
    watch: {
        apiInfos: {
            handler() {
                this.getItemsFromDb();
            },
            deep: true
        },
        activeItem(val) {
            this.getActiveItemDetails(val);
        },
        contentModal(val) {
            if (val === false) {
                this.clickedLink = false;
            }
        }
    }
})
</script>

<style lang="scss">
.v-skeleton-loader__image.v-skeleton-loader__bone {
    height: 100%;
}
</style>
