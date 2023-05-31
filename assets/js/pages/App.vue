<template>
    <div id="gradient">
<!--        <v-app>-->
        <app-bar></app-bar>

        <router-view></router-view>
<!--        </v-app>-->
    </div>
</template>

<script>
import {defineComponent} from 'vue';
import AppBar from "@pages/components/app-bar.vue";

export default defineComponent({
    name: "App",
    components: {AppBar}
})
</script>

<style scoped>

</style>
