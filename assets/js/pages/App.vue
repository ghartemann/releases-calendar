<template>
    <div id="gradient">
        <router-view></router-view>
    </div>
</template>

<script>
import {defineComponent} from 'vue';

export default defineComponent({
    name: "App"
})
</script>

<style scoped>
#gradient {
    background: #0f2027; /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #0f2027, #203a43, #2c5364); /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #0f2027, #203a43, #2c5364); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
</style>
