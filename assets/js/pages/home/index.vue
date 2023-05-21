<template>
    <div class="tw-container tw-mx-auto tw-pt-5">
        <div class="tw-flex tw-flex-col tw-justify-center">
            <h1 class="tw-text-9xl tw-text-center">Releases calendar</h1>

            <items-mosaic :api-infos="apiInfos.movies" type="movies"></items-mosaic>
            <items-mosaic :api-infos="apiInfos.tv" type="tv"></items-mosaic>
            <items-mosaic :api-infos="apiInfos.games" type="games"></items-mosaic>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import moment from "moment";
import ItemsMosaic from "@pages/home/components/items-mosaic.vue";

export default defineComponent({
    name: "Home",
    components: {ItemsMosaic},
    data: () => ({
        upcomingMovies: [],
        loadingUpcoming: false,
        apiInfos: {
            movies: {
                specificInfos: {
                    url: 'https://api.themoviedb.org/3/discover/movie',
                    urlPosters: 'https://www.themoviedb.org/t/p/w440_and_h660_face',
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
                }
            },
            tv: {
                specificInfos: {
                    url: 'https://api.themoviedb.org/3/discover/tv',
                    urlPosters: 'https://www.themoviedb.org/t/p/w440_and_h660_face',
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
                }
            },
            games: {
                specificInfos: {
                    url: 'https://api.rawg.io/api/games',
                    urlPosters: '',
                    dateParamName: 'released',
                    posterParamName: 'background_image',
                    titleParamName: 'name',
                },
                params: {
                    dates: new moment().format('YYYY-MM-DD') + ',' + new moment().add(6, 'months').format('YYYY-MM-DD'),
                    key: process.env.RAWG_API_KEY
                }
            }
        }
    })
})
</script>

<style scoped>

</style>
