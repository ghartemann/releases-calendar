<template>
    <div class="tw-container tw-mx-auto tw-pt-20">
        <div class="tw-flex tw-flex-col tw-justify-center tw-items-center">
            <h1 class="tw-text-7xl tw-font-semibold tw-text-center">
                All your <span class="tw-text-accent">release</span> date<br>
                information in <span class="tw-text-accent">one</span> place.
            </h1>

            <div class="tw-flex tw-gap-3 tw-w-fit tw-items-center tw-mt-16">
                <h2 class="tw-text-3xl">Upcoming</h2>

                <v-select v-model="displayType" :items="['movies', 'tv', 'games']"
                          variant="outlined" class="tw-text-white"
                          hide-details
                          base-color="white" color="white" rounded>
                    <template #selection="{ item, index }">
                        <h2 class="tw-text-3xl">{{ item.title }}</h2>
                    </template>
                </v-select>
            </div>

            <items-mosaic :api-infos="apiInfos[displayType]" :type="displayType"></items-mosaic>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import moment from "moment";
import ItemsMosaic from "@pages/home/components/items-mosaic.vue";
import axios from "axios";
import AppBar from "@pages/components/app-bar.vue";

export default defineComponent({
    name: "Home",
    components: {AppBar, ItemsMosaic},
    data: () => ({
        displayType: 'movies',
        apiInfos: {
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
                    page: 1,
                    sort_by: 'popularity.desc',
                    with_release_type: '2|3',
                    'primary_release_date.gte': new moment().format('YYYY-MM-DD'),
                    'primary_release_date.lte': new moment().add(6, 'months').format('YYYY-MM-DD'),
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
                    dateParamName: 'release_date',
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
                    'first_air_date.lte': new moment().add(6, 'months').format('YYYY-MM-DD'),
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
                    dates: new moment().format('YYYY-MM-DD') + ',' + new moment().add(6, 'months').format('YYYY-MM-DD'),
                    key: process.env.RAWG_API_KEY
                }
            },
            loading: false
        },
    })
})
</script>

<style scoped lang="scss">
.v-field__outline__start {
    border: 0 !important;
}
</style>
