<template>
    <div v-if="links.length">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, linkIndex) in links" :key="linkIndex">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-2 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label"></div>

                <Link v-else
                    class="mr-1 mb-1 px-4 py-2 text-sm leading-4 border text-gray-500 rounded hover:bg-white hover:text-blue-500 hover:border-blue-300 hover:font-bold focus:border-indigo-500 inline-block focus:text-indigo-500"
                    :class="{ 'bg-blue-400 text-white': link.active }" :href="link.url">
                <span v-html="link.label"></span>
                </Link>
            </template>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
    links: [Array],
});
</script>

<style lang="scss" scoped></style>
