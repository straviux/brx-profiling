<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
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
        </div>
    </AdminLayout>
</template>
