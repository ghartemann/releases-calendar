<template>
    <div class="tw-flex tw-justify-between">
        <h3 class="tw-text-black">{{ item.title }}</h3>
        <div class="tw-text-black">{{ item.release_date }}</div>
    </div>
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
