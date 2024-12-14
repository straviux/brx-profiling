<script setup>
import { computed } from 'vue'
import { Head } from "@inertiajs/vue3";
import { usePage } from '@inertiajs/vue3'
import AdminLayout from "@/Layouts/AdminLayout.vue";


const page = usePage()
const localip = computed(() => page.props.localip)
</script>

<template>

    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>Dashboard</template>
        <div class="py-4">
            <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Welcome {{ $page.props.auth.user.username }}
                    </div>
                </div>
            </div> -->
            <h1>Welcome {{ $page.props.auth.user.name }}</h1>
            <div class="flex">You can access this site on your local network thru
                <pre class="font-semibold"> <span class="underline">{{ localip }}:9000</span></pre>
            </div>
        </div>
    </AdminLayout>
</template>
