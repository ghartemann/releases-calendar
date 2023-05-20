<template>
    <div class="tw-flex tw-justify-center">
        <div class="tw-max-w-screen-lg tw-flex tw-flex-col tw-justify-center">
            <h1 class="tw-text-9xl">Releases calendar</h1>

            <div>
                <div>Upcoming</div>

                <div class="tw-flex tw-w-full">
                    <div>
                        <div>Films</div>

                        <div v-if="upcomingMovies.length === 0">Loading...</div>

                        <div v-else class="tw-flex tw-gap-y-2 tw-overflow-scroll tw-w-full">
                            <div v-for="(movie, index) in upcomingMovies" :key="index" class="tw-flex tw-gap-2">
                                <img :src="getUrl(movie.poster_path)" :alt="movie.title"
                                     class="tw-rounded-xl tw-w-[50rem]">

                                <div class="tw-absolute tw-text-white">
                                    <div>{{ movie.title }}</div>
                                    <div>{{ frenchizeDate(movie.release_date) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--                    <div class="tw-w-1/3">-->
                    <!--                        <div>Séries</div>-->
                    <!--                        <ul>-->
                    <!--                            <li v-for="(tv, index) in stuff.tv" :key="index">-->
                    <!--                                {{ tv.releaseDate }} - {{ tv.title }}-->
                    <!--                            </li>-->
                    <!--                        </ul>-->
                    <!--                    </div>-->

                    <!--                    <div class="tw-w-1/3">-->
                    <!--                        <div>Jeux</div>-->
                    <!--                        <ul>-->
                    <!--                            <li v-for="(game, index) in stuff.games" :key="index">-->
                    <!--                                {{ game.releaseDate }} - {{ game.title }}-->
                    <!--                            </li>-->
                    <!--                        </ul>-->
                    <!--                    </div>-->
                </div>
            </div>

            <!--            <div>-->
            <!--                <div>My lists</div>-->

            <!--                <div class="tw-flex tw-w-full">-->
            <!--                    <div class="tw-w-1/3">-->
            <!--                        <div>Films</div>-->
            <!--                        <ul>-->
            <!--                            <li v-for="(movie, index) in stuff.movies" :key="index">-->
            <!--                                {{ movie.releaseDate }} - {{ movie.title }}-->
            <!--                            </li>-->
            <!--                        </ul>-->
            <!--                    </div>-->

            <!--                    <div class="tw-w-1/3">-->
            <!--                        <div>Séries</div>-->
            <!--                        <ul>-->
            <!--                            <li v-for="(tv, index) in stuff.tv" :key="index">-->
            <!--                                {{ tv.releaseDate }} - {{ tv.title }}-->
            <!--                            </li>-->
            <!--                        </ul>-->
            <!--                    </div>-->

            <!--                    <div class="tw-w-1/3">-->
            <!--                        <div>Jeux</div>-->
            <!--                        <ul>-->
            <!--                            <li v-for="(game, index) in stuff.games" :key="index">-->
            <!--                                {{ game.releaseDate }} - {{ game.title }}-->
            <!--                            </li>-->
            <!--                        </ul>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import axios from "axios";
import moment from "moment";

export default defineComponent({
    name: "Home",
    data: () => ({
        upcomingMovies: [],
        stuff: {
            games: [
                {title: 'Game 1', releaseDate: '2021-01-01'},
                {title: 'Game 2', releaseDate: '2021-01-01'},
                {title: 'Game 3', releaseDate: '2021-01-01'},
                {title: 'Game 4', releaseDate: '2021-01-01'},
                {title: 'Game 5', releaseDate: '2021-01-01'},
                {title: 'Game 6', releaseDate: '2021-01-01'},
            ],
            movies: [
                {title: 'Movie 1', releaseDate: '2021-01-01'},
                {title: 'Movie 2', releaseDate: '2021-01-01'},
                {title: 'Movie 3', releaseDate: '2021-01-01'},
                {title: 'Movie 4', releaseDate: '2021-01-01'},
                {title: 'Movie 5', releaseDate: '2021-01-01'},
                {title: 'Movie 6', releaseDate: '2021-01-01'},
            ],
            tv: [
                {title: 'TV show 1', releaseDate: '2021-01-01'},
                {title: 'TV show 2', releaseDate: '2021-01-01'},
                {title: 'TV show 3', releaseDate: '2021-01-01'},
                {title: 'TV show 4', releaseDate: '2021-01-01'},
                {title: 'TV show 5', releaseDate: '2021-01-01'},
                {title: 'TV show 6', releaseDate: '2021-01-01'},
            ],
        },
        loadingUpcoming: false,
    }),
    created() {
        this.getUpcomingMovies();
    },
    computed: {
        maxDate() {
            return new moment().add(6, 'months');
        }
    },
    methods: {
        getUpcomingMovies() {
            this.loadingUpcoming = true;

            axios.get('https://api.themoviedb.org/3/discover/movie', {
                params: {
                    include_adult: false,
                    include_video: false,
                    language: 'en_US',
                    page: 1,
                    sort_by: 'primary_release_date.desc',
                    with_release_type: '2|3',
                    release_date_gte: new moment().format('YYYY-MM-DD'),
                    release_date_lte: new moment().add(6, 'months').format('YYYY-MM-DD'),
                    api_key: process.env.TMDB_API_KEY,
                }
            })
                .then((r) => {
                    this.upcomingMovies = r.data.results;
                }).catch((error) => {
                console.error(error);
            }).finally(() => {
                this.loadingUpcoming = false;
            });
        },
        getUrl(posterPath) {
            return 'https://www.themoviedb.org/t/p/w440_and_h660_face' + posterPath;
        },
        frenchizeDate(date) {
            return new moment(date).format('DD/MM/YYYY');
        }
    }
})
</script>

<style scoped>

</style>
