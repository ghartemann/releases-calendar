<template>
    <div class="tw-w-full tw-flex tw-justify-center tw-text-center tw-mt-20">
        <h1 class="tw-text-5xl">
            Thanks to the Vuetify dev team, this<br>
            feature has been <span class="tw-text-accent">postponed</span>.
        </h1>
<!--            <v-calendar-->
<!--                    ref="calendar"-->
<!--                    v-model="value"-->
<!--                    :weekdays="[0, 1, 2, 3, 4, 5, 6]"-->
<!--                    type="month"-->
<!--                    :events="events"-->
<!--                    event-overlap-mode="stack"-->
<!--                    :event-overlap-threshold="30"-->
<!--            ></v-calendar>-->

    </div>
</template>

<script>
import {defineComponent} from 'vue';

export default defineComponent({
    name: "Calendar",
    data: () => {
        return {
            events: [],
            value: null
        }
    }
});
</script>

<style scoped>

</style>
