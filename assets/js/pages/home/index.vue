<template>
    <div class="tw-flex tw-justify-end tw-p-3">
        <h3 class="tw-text-2xl tw-cursor-pointer">
            Login
            <v-dialog v-model="loginModal"
                      activator="parent"
                      transition="dialog-bottom-transition"
                      width="30%">
                <v-card class="tw-p-4 !tw-rounded-2xl">
                    <v-card-text class="tw-flex tw-flex-col tw-items-center">
                        <v-form class="tw-w-full">
                            <v-text-field
                                    v-model="loginForm.email"
                                    label="Email"
                                    required
                                    variant="outlined"
                            ></v-text-field>

                            <v-text-field
                                    v-model="loginForm.password"
                                    label="Password"
                                    type="password"
                                    required
                                    variant="outlined"
                            ></v-text-field>
                        </v-form>

                        <div v-if="alreadyAnAccount === true" class="tw-flex tw-flex-col tw-gap-2 tw-w-1/2">
                            <v-btn color="black" flat @click="accountLogin">Login</v-btn>
                            <v-btn color="white" flat @click="alreadyAnAccount = false">Create an account</v-btn>
                        </div>

                        <div v-else class="tw-flex tw-flex-col tw-gap-2 tw-w-1/2">
                            <v-btn color="black" flat @click="accountCreate">Create my account</v-btn>
                            <v-btn color="white" flat @click="alreadyAnAccount = true">I already have one</v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </h3>
    </div>

    <div class="tw-container tw-mx-auto tw-pt-5">
        <div class="tw-flex tw-flex-col tw-justify-center tw-items-center">
            <h1 class="tw-text-7xl tw-font-semibold tw-text-center">
                All your <span class="tw-text-amber-500">release</span> date<br>
                information in <span class="tw-text-amber-500">one</span> place.
            </h1>

            <div class="tw-flex tw-gap-3 tw-w-1/5 tw-items-center tw-mt-16">
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

export default defineComponent({
    name: "Home",
    components: {ItemsMosaic},
    data: () => ({
        upcomingMovies: [],
        loginModal: false,
        loginForm: {
            email: '',
            password: ''
        },
        alreadyAnAccount: true,
        loadingUpcoming: false,
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
            },
            loading: false
        },
    }),
    methods: {
        accountLogin() {

        },
        accountCreate() {
            this.loading = true;

            axios.post('/api/register', {
                email: this.loginForm.email,
                password: this.loginForm.password
            }).then(() => {
                this.loginModal = false;
            }).catch((r) => {
                console.error(r);
            }).finally(() => {
                this.loading = false;
            });
        }
    }
})
</script>

<style scoped lang="scss">
.v-field__outline__start {
    border: 0 !important;
}
</style>
