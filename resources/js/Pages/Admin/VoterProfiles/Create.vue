<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import VueMultiselect from "vue-multiselect";

const positions = [
    { id: 1, name: "Coordinator" },
    { id: 2, name: "Leader" },
    { id: 3, name: "Subleader" },
    { id: 4, name: "Member" },
];
const form = useForm({
    name: "",
    firstname: "",
    lastname: "",
    middlename: "",
    barangay: "",
    birthdate: "",
    purok: "",
    contact_no: "",
    precinct_no: "",
    gender: "",
    position: "",
    remarks: "",
});
</script>

<template>
    <Head title="Create new role" />

    <AdminLayout>
        <template #header>Voter Profile</template>

        <div class="max-w-5xl mx-auto py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-indigo-700">
                    Create New Voter Profile
                </h1>
                <Link
                    :href="route('votersprofile.index')"
                    class="text-white font-semibold px-3 py-2 bg-indigo-500 hover:bg-indigo-700 rounded"
                    >Back</Link
                >
            </div>
            <div
                class="mt-6 max-w-5xl mx-auto bg-gray-100 shadow-lg rounded-lg p-6"
            >
                <form @submit.prevent="form.post(route('votersprofile.store'))">
                    <div class="flex justify-between gap-4">
                        <div class="w-1/3">
                            <InputLabel for="lastname" value="Last Name" />

                            <TextInput
                                autofocus
                                id="lastname"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.lastname"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.lastname"
                            />
                        </div>
                        <div class="w-1/3">
                            <InputLabel for="firstname" value="First Name" />

                            <TextInput
                                id="firstname"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.firstname"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.firstname"
                            />
                        </div>
                        <div class="w-1/3">
                            <InputLabel for="middlename" value="Middle Name" />

                            <TextInput
                                id="middlename"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.middlename"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.middlename"
                            />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between gap-4">
                        <div class="w-1/3">
                            <InputLabel for="barangay" value="Barangay" />

                            <TextInput
                                id="barangay"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.barangay"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.barangay"
                            />
                        </div>
                        <div class="w-1/3">
                            <InputLabel for="purok" value="Purok/Sitio" />

                            <TextInput
                                id="purok"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.purok"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.purok"
                            />
                        </div>
                        <div class="w-1/3">
                            <InputLabel
                                for="contact_no"
                                value="Contact Number"
                            />

                            <TextInput
                                id="contact_no"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.contact_no"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.contact_no"
                            />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between gap-4">
                        <div class="w-1/3">
                            <InputLabel
                                for="precinct_no"
                                value="Precinct Number"
                            />

                            <TextInput
                                id="precinct_no"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.precinct_no"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.precinct_no"
                            />
                        </div>
                        <div class="w-1/3">
                            <InputLabel for="birthdate" value="Birthdate" />

                            <TextInput
                                id="birthdate"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.birthdate"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.birthdate"
                            />
                        </div>
                        <div class="w-1/3">
                            <InputLabel for="gender" value="Gender" />

                            <TextInput
                                id="gender"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.gender"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.gender"
                            />
                        </div>
                    </div>

                    <div class="mt-4 flex">
                        <div class="w-1/2">
                            <InputLabel for="position" value="Position" />
                            <select
                                name="position"
                                id="position"
                                v-model="form.position"
                            >
                                <option
                                    :value="pos.name"
                                    v-for="pos in positions"
                                    :key="pos.id"
                                >
                                    {{ pos.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 w-1/2">
                        <InputLabel for="remarks" value="Remarks" />

                        <TextInput
                            id="remarks"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.remarks"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.remarks"
                        />
                    </div>
                    <div class="flex items-center mt-4">
                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Create
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
