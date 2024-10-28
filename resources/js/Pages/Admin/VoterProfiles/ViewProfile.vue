<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps({
    profile: Object,
});
</script>

<template>
    <Head title="Profile" />

    <AdminLayout>
        <template #header>Profile</template>
        <div class="py-4 flex">
            <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Welcome {{ $page.props.auth.user.username }}
                    </div>
                </div>
            </div> -->
            <!-- {{ props.profile }} -->
            <div class="md:w-1/2 lg:w-1/4">
                <div>
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        v-model="profile.name"
                        type="text"
                        class="mt-1 block w-full uppercase"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="position" value="Position" />

                    <TextInput
                        id="position"
                        v-model="profile.position"
                        type="text"
                        class="mt-1 block w-full uppercase"
                    />
                </div>
                <div class="mt-4">
                    <InputLabel for="precint" value="Precint No" />

                    <TextInput
                        id="precint"
                        v-model="profile.precinct_no"
                        type="text"
                        class="mt-1 block w-full uppercase"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
