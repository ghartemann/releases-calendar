<template>
    <div class="tw-flex tw-justify-between tw-p-3">
        <div class="tw-flex tw-gap-10 tw-items-baseline">
            <router-link class="tw-text-white tw-font-bold tw-text-3xl" :to="{name: 'homepage'}">
                <h3>Release<span class="tw-text-accent">Prophet</span></h3>
            </router-link>

            <router-link v-for="link in links" :to="{name: link.routeName}">
                <h4 class="tw-text-2xl">{{ link.displayName }}</h4>
            </router-link>
        </div>

        <h4 class="tw-text-2xl tw-cursor-pointer">
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
        </h4>
    </div>
</template>

<script>
import {defineComponent} from "vue";
import axios from "axios";

export default defineComponent({
    name: "AppBar",
    data: () => ({
        loginModal: false,
        alreadyAnAccount: true,
        loginForm: {
            email: '',
            password: ''
        },
        links: [
            {
                routeName: 'homepage',
                displayName: 'Releases'
            },
            {
                routeName: 'calendar',
                displayName: 'Calendar'
            }
        ]
    }),
    methods: {
        accountLogin() {
            console.log('non');
        },
        accountCreate() {
            this.loading = true;

            axios.post('/user/register', {
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
});
</script>

<style scoped>

</style>
