<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useStorage } from "@vueuse/core";
import { ref, onMounted, computed } from "vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TabLink from "@/Components/TabLink.vue";
import VueMultiselect from "vue-multiselect";
import {
    MagnifyingGlassIcon,
    PencilSquareIcon,
    EyeIcon,
} from "@heroicons/vue/20/solid";

import EditModal from "@/Pages/Admin/VoterProfiles/Modal/EditModal.vue";

const props = defineProps({
    profile: Object,
    barangays: Array,
    voterprofiles: {
        type: Array,
    },
});
const barangayOptions = ref([]);
const gridview = useStorage("gridview", false);
const form = useForm({});
const currentVoterPosition = route().params.position || null;
const showConfirmDeleteVoterProfileModal = ref(false);
const modalVoterProfileData = ref({ id: null, name: null });

const confirmDeleteVoterProfile = (profileID, profileName) => {
    showConfirmDeleteVoterProfileModal.value = true;
    modalVoterProfileData.value.id = profileID;
    modalVoterProfileData.value.name = profileName;
};
const closeModal = () => {
    showConfirmDeleteVoterProfileModal.value = false;
};
const deleteRole = (voterProfileID) => {
    form.delete(route("votersprofile.destroy", voterProfileID), {
        onSuccess: () => closeModal(),
    });
};

onMounted(() => {
    barangayOptions.value = props.barangays.map((bgy) => bgy.barangay_name);
    console.log(props);
});
</script>

<template>
    <Head title="Voter's Profile" />

    <AdminLayout>
        <template #header>Voter's Profile</template>

        <div class="max-w-full mx-auto py-4">
            <div
                class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700 flex justify-center items-center"
            >
                <ul class="flex flex-wrap -mb-px">
                    <li class="me-2">
                        <TabLink
                            :href="route('votersprofile.showposition', 'all')"
                            :active="
                                route().current(
                                    'votersprofile.showposition',
                                    'all'
                                )
                            "
                            >All</TabLink
                        >
                    </li>
                    <li class="me-2">
                        <TabLink
                            :href="
                                route(
                                    'votersprofile.showposition',
                                    'coordinator'
                                )
                            "
                            :active="
                                route().current(
                                    'votersprofile.showposition',
                                    'coordinator'
                                )
                            "
                            >Coordinator</TabLink
                        >
                    </li>
                    <li class="me-2">
                        <TabLink
                            :href="
                                route('votersprofile.showposition', 'leader')
                            "
                            :active="
                                route().current(
                                    'votersprofile.showposition',
                                    'leader'
                                )
                            "
                            >Leader</TabLink
                        >
                    </li>
                    <li class="me-2">
                        <TabLink
                            :href="
                                route('votersprofile.showposition', 'subleader')
                            "
                            :active="
                                route().current(
                                    'votersprofile.showposition',
                                    'subleader'
                                )
                            "
                            >SubLeader</TabLink
                        >
                    </li>
                    <li>
                        <TabLink
                            :href="
                                route('votersprofile.showposition', 'member')
                            "
                            :active="
                                route().current(
                                    'votersprofile.showposition',
                                    'member'
                                )
                            "
                            >Member</TabLink
                        >
                    </li>
                </ul>
                <Link
                    :href="route('votersprofile.create')"
                    class="text-white font-semibold px-3 py-2 bg-sky-500 hover:bg-sky-600 rounded ml-auto shadow-lg"
                    >Add Profile</Link
                >
            </div>

            <div class="flex gap-4 mt-12 mb-4">
                <!--search bar -->
                <div class="flex gap-4 w-2/3">
                    <div class="w-1/2">
                        <div
                            class="relative flex items-center text-gray-400 focus-within:text-sky-500"
                        >
                            <span
                                class="absolute left-4 h-6 flex items-center pr-3 border-r border-gray-300"
                            >
                                <MagnifyingGlassIcon class="h-5 w-5" />
                            </span>
                            <input
                                type="search"
                                name="leadingIcon"
                                id="leadingIcon"
                                placeholder="Search name"
                                class="w-full pl-14 pr-4 py-2.5 rounded-xl text-sm text-gray-600 outline-none border border-gray-300 focus:border-cyan-300 transition"
                            />
                        </div>
                    </div>

                    <div class="w-1/2 flex items-center gap-2">
                        <p class="text-gray-600 text-sm">Filter</p>
                        <VueMultiselect
                            v-model="form.barangay"
                            :options="barangayOptions"
                            :close-on-select="true"
                            placeholder="Select barangay"
                        />
                    </div>
                </div>
                <div
                    class="flex items-center gap-2 ml-auto px-2 mr-2"
                    v-if="currentVoterPosition != 'all'"
                >
                    <label
                        class="relative inline-flex cursor-pointer items-center"
                    >
                        <input
                            id="switch"
                            type="checkbox"
                            class="peer sr-only"
                            v-model="gridview"
                        />
                        <label for="switch" class="hidden"></label>
                        <div
                            class="peer h-6 w-11 rounded-full border bg-slate-200 after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-slate-800 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-green-300"
                        ></div>
                    </label>
                    <p class="text-xs text-gray-600" v-if="gridview">Grid</p>
                    <p class="text-xs text-gray-600" v-else>Table</p>
                </div>
            </div>
            <div class="mt-6 bg-white" v-if="currentVoterPosition == 'all'">
                <Table>
                    <template #header>
                        <TableRow class="border-b">
                            <TableHeaderCell>#</TableHeaderCell>
                            <TableHeaderCell>Name</TableHeaderCell>
                            <TableHeaderCell>Position</TableHeaderCell>
                            <TableHeaderCell>Barangay</TableHeaderCell>
                            <TableHeaderCell>Action</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow
                            v-for="(voter, index) in voterprofiles"
                            :key="'voter_' + voter.id"
                        >
                            <TableDataCell>{{ index + 1 }}</TableDataCell>
                            <TableDataCell>{{
                                voter.lastname +
                                ", " +
                                voter.firstname +
                                " " +
                                (voter.middlename || "")
                            }}</TableDataCell>
                            <TableDataCell>{{ voter.position }}</TableDataCell>
                            <TableDataCell>{{ voter.barangay }}</TableDataCell>
                            <TableDataCell class="space-x-6">
                                <Link
                                    :href="
                                        route('votersprofile.edit', voter.id)
                                    "
                                    class="text-green-500 hover:text-green-600"
                                    >Edit</Link
                                >

                                <button
                                    class="text-red-500 hover:text-red-600"
                                    @click="
                                        confirmDeleteVoterProfile(
                                            voter.id,
                                            voter.name
                                        )
                                    "
                                >
                                    Delete
                                </button>
                                <Modal
                                    maxWidth="lg"
                                    :show="showConfirmDeleteVoterProfileModal"
                                    @close="closeModal"
                                >
                                    <div class="p-6">
                                        <h2
                                            class="text-lg font-semibold text-slate-800"
                                        >
                                            Are you sure you want to delete this
                                            profile?
                                        </h2>
                                        <p
                                            class="mt-4 bg-slate-100 p-2 text-center text-red-700 font-semibold"
                                        >
                                            "{{ modalVoterProfileData.name }}"
                                        </p>

                                        <div class="mt-6 flex space-x-4">
                                            <DangerButton
                                                @click="
                                                    deleteRole(
                                                        modalVoterProfileData.id
                                                    )
                                                "
                                            >
                                                Delete</DangerButton
                                            >
                                            <SecondaryButton @click="closeModal"
                                                >Cancel</SecondaryButton
                                            >
                                        </div>
                                    </div>
                                </Modal>
                            </TableDataCell>
                        </TableRow>
                    </template>
                </Table>
            </div>

            <div class="mt-6" v-else-if="currentVoterPosition != 'all'">
                <ul
                    v-if="gridview"
                    role="list"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <li
                        class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow"
                        v-for="(profile, index) in voterprofiles"
                        :key="'profile_' + profile.id"
                    >
                        <div
                            class="flex w-full items-start justify-between space-x-6 px-2 py-2.5"
                        >
                            <p class="text-gray-400 text-xs absolute ml-1 mt-1">
                                #{{ index + 1 }}
                            </p>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <h3
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        {{ profile.name }}
                                    </h3>
                                </div>
                                <p
                                    class="mt-1 font-semibold text-[12px] text-gray-500"
                                >
                                    {{ profile.barangay }} -
                                    {{ profile.precinct_no }}
                                </p>
                            </div>
                            <!-- <img
                                class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-300"
                                src="https://qph.cf2.quoracdn.net/main-thumb-554097988-200-xietklpojlcioqxaqgcyykzfxblvoqrb.jpeg"
                                alt=""
                            /> -->
                        </div>
                        <div
                            class="px-6 py-2"
                            v-if="currentVoterPosition == 'coordinator'"
                        >
                            <h3 class="text-gray-600 ml-4">Leaders</h3>
                            <div class="mt-1">
                                <div class="tree-view text-sm">
                                    <ul class="list-none pl-5">
                                        <li
                                            class="py-1"
                                            v-for="(
                                                leader, i
                                            ) in profile.members"
                                            :key="'_leader_' + i"
                                        >
                                            <span
                                                class="text-gray-500 text-[11px]"
                                                >{{ i + 1 }}.
                                            </span>
                                            <span
                                                class="text-gray-700 tracking-wide text-[12px]"
                                            >
                                                {{ leader.name }}</span
                                            >
                                        </li>
                                        <li
                                            class="py-1 text-gray-500 text-[11px]"
                                            v-if="profile.members.length < 7"
                                            v-for="index in 7 -
                                            profile.members.length"
                                        >
                                            {{
                                                profile.members.length + index
                                            }}. N/A
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-6 py-2"
                            v-else-if="currentVoterPosition == 'leader'"
                        >
                            <h3 class="text-gray-600 ml-4">SubLeaders</h3>
                            <div class="mt-1">
                                <div class="tree-view text-sm">
                                    <ul class="list-none pl-5">
                                        <li
                                            class="py-1"
                                            v-for="(
                                                member, i
                                            ) in profile.members"
                                            :key="'_member_' + i"
                                        >
                                            <span
                                                class="text-gray-500 text-[11px]"
                                                >{{ i + 1 }}.
                                            </span>
                                            <span
                                                class="text-gray-700 tracking-wide text-[12px]"
                                            >
                                                {{ member.name }}</span
                                            >
                                        </li>
                                        <li
                                            class="py-1 text-gray-500 text-[11px]"
                                            v-if="profile.members.length < 3"
                                            v-for="index in 3 -
                                            profile.members.length"
                                        >
                                            {{
                                                profile.members.length + index
                                            }}. N/A
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-6 py-2"
                            v-else-if="currentVoterPosition == 'subleader'"
                        >
                            <h3 class="text-gray-600 ml-4">Members</h3>
                            <div class="mt-1">
                                <div class="tree-view text-sm">
                                    <ul class="list-none pl-5">
                                        <li
                                            class="py-1"
                                            v-for="(
                                                member, i
                                            ) in profile.members"
                                            :key="'_member_' + i"
                                        >
                                            <span
                                                class="text-gray-500 text-[11px]"
                                                >{{ i + 1 }}.
                                            </span>
                                            <span
                                                class="text-gray-700 tracking-wide text-[12px]"
                                            >
                                                {{ member.name }}</span
                                            >
                                        </li>
                                        <li
                                            class="py-1 text-gray-500 text-[11px]"
                                            v-if="profile.members.length < 3"
                                            v-for="index in 3 -
                                            profile.members.length"
                                        >
                                            {{
                                                profile.members.length + index
                                            }}. N/A
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div
                            class="px-4 py-2 flex"
                            v-else-if="currentVoterPosition == 'member'"
                        >
                            <p class="text-gray-600 ml-4 text-[12px]">
                                Leader:
                            </p>
                            <p class="ml-2 text-[12px] text-gray-500">
                                {{ profile.leader.name }}
                            </p>
                        </div>
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200">
                                <div class="flex w-0 flex-1">
                                    <Link
                                        :href="
                                            route(
                                                'votersprofile.showposition',
                                                {
                                                    position:
                                                        currentVoterPosition,
                                                    id: profile.id,
                                                }
                                            )
                                        "
                                        preserve-state
                                        preserve-scroll
                                        class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                                    >
                                        <PencilSquareIcon
                                            class="h-5 w-5 text-gray-400"
                                        />
                                        Edit
                                    </Link>
                                </div>

                                <div class="-ml-px flex w-0 flex-1">
                                    <a
                                        href="tel:+1-202-555-0170"
                                        class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-3 rounded-br-lg border border-transparent py-4 text-sm font-semibold text-gray-900"
                                    >
                                        <EyeIcon
                                            class="h-5 w-5 text-gray-400"
                                        />
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <Table v-else>
                    <template #header>
                        <TableRow class="border-b">
                            <TableHeaderCell>#</TableHeaderCell>
                            <TableHeaderCell>Name</TableHeaderCell>
                            <TableHeaderCell>Position</TableHeaderCell>
                            <TableHeaderCell>Barangay</TableHeaderCell>
                            <TableHeaderCell>Action</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow
                            v-for="(voter, index) in voterprofiles"
                            :key="'voter_' + voter.id"
                        >
                            <TableDataCell>{{ index + 1 }}</TableDataCell>
                            <TableDataCell>{{
                                voter.lastname +
                                ", " +
                                voter.firstname +
                                " " +
                                (voter.middlename || "")
                            }}</TableDataCell>
                            <TableDataCell>{{ voter.position }}</TableDataCell>
                            <TableDataCell>{{ voter.barangay }}</TableDataCell>
                            <TableDataCell class="space-x-6">
                                <Link
                                    :href="
                                        route('votersprofile.edit', voter.id)
                                    "
                                    :only="['profile']"
                                    class="text-green-500 hover:text-green-600"
                                    >Edit</Link
                                >

                                <button
                                    class="text-red-500 hover:text-red-600"
                                    @click="
                                        confirmDeleteVoterProfile(
                                            voter.id,
                                            voter.name
                                        )
                                    "
                                >
                                    Delete
                                </button>
                                <Modal
                                    maxWidth="lg"
                                    :show="showConfirmDeleteVoterProfileModal"
                                    @close="closeModal"
                                >
                                    <div class="p-6">
                                        <h2
                                            class="text-lg font-semibold text-slate-800"
                                        >
                                            Are you sure you want to delete this
                                            profile?
                                        </h2>
                                        <p
                                            class="mt-4 bg-slate-100 p-2 text-center text-red-700 font-semibold"
                                        >
                                            "{{ modalVoterProfileData.name }}"
                                        </p>

                                        <div class="mt-6 flex space-x-4">
                                            <DangerButton
                                                @click="
                                                    deleteRole(
                                                        modalVoterProfileData.id
                                                    )
                                                "
                                            >
                                                Delete</DangerButton
                                            >
                                            <SecondaryButton @click="closeModal"
                                                >Cancel</SecondaryButton
                                            >
                                        </div>
                                    </div>
                                </Modal>
                            </TableDataCell>
                        </TableRow>
                    </template>
                </Table>
                <EditModal
                    :profile="props.profile"
                    :barangays="barangayOptions"
                    :position="currentVoterPosition"
                />
            </div>
        </div>
    </AdminLayout>
</template>
<style>
.multiselect__input {
    min-height: 24px !important;
    font-size: 12px !important;
    border-radius: 8px !important;
}
.multiselect__option {
    font-size: 14px !important;
}
/* .multiselect__option--highlight {
    background: #0369a1 !important;
} */
.multiselect__input::placeholder {
    text-transform: none;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
