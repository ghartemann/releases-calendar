<template>
    <div class="tw-flex tw-flex-col tw-gap-24 tw-items-center">
        <div class="tw-flex tw-justify-between tw-container tw-items-baseline">
            <router-link :to="{ name: 'homepage' }">
                <h2 class="tw-text-2xl">
                    <v-icon>mdi-chevron-left</v-icon>
                    Back
                </h2>
            </router-link>

            <h1 class="tw-text-center tw-text-5xl">
                All the <span class="tw-text-accent">{{ type }}</span> you can get.
            </h1>

            <div></div>
        </div>

        <div class="tw-grid tw-grid-cols-4 tw-gap-5 tw-container">
            <div class="tw-col-span-2">
                <items-column
                    :api-infos="apiInfos[type]"
                    :period="6"
                    :type="type.toString()"
                    @update-items="(val) => items = val">
                </items-column>
            </div>

            <div class="tw-col-span-2">
                <my-list :items="items"></my-list>
            </div>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue';

import itemsColumn from "@pages/type/components/items-column.vue";
import moment from "moment/moment";
import MyList from "@pages/type/components/my-list.vue";

export default defineComponent({
    name: "type",
    components: {MyList, itemsColumn},
    data: () => ({
        displayTime: 6,
        items: []
    }),
    computed: {
        type() {
            return this.$route.params.type;
        },
        apiInfos() {
            return {
                movies: {
                    specificInfos: {
                        url: 'https://api.themoviedb.org/3/discover/movie',
                        urlDetails: 'https://api.themoviedb.org/3/movie/',
                        urlPosters: 'https://www.themoviedb.org/t/p/w440_and_h660_face',
                        urlBackdrops: 'https://www.themoviedb.org/t/p/w1920_and_h800_multi_faces',
                        dateParamName: 'release_date',
                        posterParamName: 'poster_path',
                        titleParamName: 'title',
                    },
                    params: {
                        include_adult: false,
                        include_video: true,
                        language: 'fr_FR',
                        page: 3,
                        sort_by: 'popularity.desc',
                        with_release_type: '2|3',
                        'primary_release_date.gte': new moment().format('YYYY-MM-DD'),
                        'primary_release_date.lte': new moment().add(this.displayTime, 'months').format('YYYY-MM-DD'),
                        api_key: process.env.TMDB_API_KEY
                    },
                    detailsParams: {
                        append_to_response: 'credits,videos',
                        api_key: process.env.TMDB_API_KEY
                    }
                },
                tv: {
                    specificInfos: {
                        url: 'https://api.themoviedb.org/3/discover/tv',
                        urlDetails: 'https://api.themoviedb.org/3/tv/',
                        urlPosters: 'https://www.themoviedb.org/t/p/w440_and_h660_face',
                        urlBackdrops: 'https://www.themoviedb.org/t/p/w1920_and_h800_multi_faces',
                        dateParamName: 'first_air_date',
                        posterParamName: 'poster_path',
                        titleParamName: 'name',
                    },
                    params: {
                        include_adult: false,
                        include_video: true,
                        language: 'en_US',
                        page: 1,
                        sort_by: 'popularity.desc',
                        'first_air_date.gte': new moment().format('YYYY-MM-DD'),
                        'first_air_date.lte': new moment().add(this.displayTime, 'months').format('YYYY-MM-DD'),
                        api_key: process.env.TMDB_API_KEY
                    },
                    detailsParams: {
                        append_to_response: 'credits,videos',
                        api_key: process.env.TMDB_API_KEY
                    }
                },
                games: {
                    specificInfos: {
                        url: 'https://api.rawg.io/api/games',
                        urlDetails: 'https://api.rawg.io/api/games/',
                        urlPosters: '',
                        dateParamName: 'released',
                        posterParamName: 'background_image',
                        titleParamName: 'name',
                    },
                    params: {
                        dates: new moment().format('YYYY-MM-DD') + ',' + new moment().add(this.displayTime, 'months').format('YYYY-MM-DD'),
                        key: process.env.RAWG_API_KEY
                    }
                },
                loading: false
            }
        }
    }
})
</script>

<style scoped>

</style>
