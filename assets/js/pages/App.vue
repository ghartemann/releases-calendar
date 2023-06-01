<template>
    <div id="gradient">
        <app-bar></app-bar>

        <div class="tw-container tw-mx-auto tw-py-20">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue';
import AppBar from "@pages/components/app-bar";

export default defineComponent({
    name: "App",
    components: {AppBar}
})
</script>

<style scoped>

</style>
