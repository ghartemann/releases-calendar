<template>
    <h3>{{ item.title }}</h3>
</template>

<script>
import {defineComponent} from 'vue'

export default defineComponent({
    name: "list-item",
    props: {
        item: {
            type: Object,
            required: true
        }
    }
})
</script>

<style scoped>

</style>
