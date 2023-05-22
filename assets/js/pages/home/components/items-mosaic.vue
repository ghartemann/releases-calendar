<template>
    <div class="tw-flex tw-flex-col tw-items-center tw-w-full tw-my-8">
        <div class="tw-flex tw-flex-col tw-justify-center tw-items-center">
            <!--            <div class="tw-text-white">params-->
            <!--                <v-icon color="white">mdi-check</v-icon>-->
            <!--            </div>-->

            <div v-if="loading">Loading...</div>

            <div v-else
                 class="tw-grid xl:tw-grid-cols-5 lg:tw-grid-cols-4 md:tw-grid-cols-3 sm:tw-grid-cols-2 tw-gap-4">
                <div v-for="(item, index) in items" :key="index"
                     @click="showModal(item)"
                     class="tw-col-span-1 tw-w-[13.75rem] tw-h-[20.625rem] tw-rounded-xl !tw-bg-cover !tw-bg-center tw-relative tw-cursor-pointer"
                     :style="`background:url('` + getUrl(item[apiInfos.specificInfos.posterParamName], 'urlPosters') + `');`"
                     @mouseover="hovered[index] = true" @mouseout="hovered[index] = false">

                    <div class="tw-flex tw-flex-col tw-justify-between tw-items-center tw-gap-2 tw-p-5 text-center tw-text-white hover:tw-bg-black hover:tw-bg-opacity-50 tw-h-full tw-w-full tw-rounded-xl"
                    >
                        <div></div>

                        <div v-show="hovered[index]">
                            <div class="tw-font-black">{{ item[apiInfos.specificInfos.titleParamName] }}</div>
                            <div>{{ frenchizeDate(item[apiInfos.specificInfos.dateParamName]) }}</div>
                        </div>

                        <div>
                            <!-- TODO: cacher le bouton + quand pas de hover-->
                            <v-btn @click.stop="addOrRemoveFromMyList(item)"
                                   v-show="hovered[index] || myList.includes(item)"
                                   :color="myList.includes(item) === true ? 'black' : ''"
                                   icon flat
                            >
                                <v-icon color="white">{{
                                        myList.includes(item) === false ? 'mdi-plus' : 'mdi-heart'
                                    }}
                                </v-icon>
                            </v-btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TODO: passer ça en composant-->
    <v-dialog v-model="contentModal"
              transition="dialog-bottom-transition"
              width="70%" max-height="80vh">
        <v-card class="!tw-rounded-2xl">
            <v-card-text class="tw-flex tw-flex-col tw-items-center !tw-p-0">
                <div :style="`background:url('` + getUrl(activeItemDetails['backdrop_path'], 'urlPosters') + `');`" class="tw-flex tw-w-full tw-bg-contain tw-bg-no-repeat">
                    <img :src="getUrl(activeItemDetails['poster_path'], 'urlPosters')"
                         class="tw-w-1/3 tw-h-1/3 tw-rounded-xl tw-m-5 tw-shadow-2xl"
                         alt="poster">

                    <div class="tw-flex tw-flex-col">
                        <h4 class="tw-text-2xl tw-font-semibold tw-bg-black tw-rounded-xl">{{ activeItemDetails.title }}</h4>

                        <div class="tw-text-white">
                            <h5 class="tw-text-xl tw-text-black tw-font-semibold">Overview</h5>
                            <div>{{ activeItemDetails.overview }}</div>
                        </div>
                    </div>
                </div>
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
        getActiveItemDetails() {
            this.loadingActiveItemDetails = true;

            if (this.apiInfos.specificInfos.urlDetails) {
                axios.get(this.apiInfos.specificInfos.urlDetails + this.activeItem.id, {params: this.apiInfos.detailsParams}).then((r) => {
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
        }
    }
})
</script>

<style scoped>

</style>
