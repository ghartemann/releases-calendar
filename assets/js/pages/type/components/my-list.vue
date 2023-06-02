<template>
    <div class="tw-bg-white tw-rounded-2xl tw-shadow-2xl tw-w-full tw-min-h-full tw-p-10">
        <h2 class="tw-text-5xl tw-text-black">My list</h2>

        <div v-for="(item, index) in items" :key="index">
            <list-item :item="item"></list-item>
        </div>
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import ListItem from "@pages/type/components/list-item.vue";

export default defineComponent({
    name: "my-list",
    components: {ListItem},
    props: {
        items: {
            type: Array,
            required: true
        }
    }
})
</script>

<style scoped>

</style>
