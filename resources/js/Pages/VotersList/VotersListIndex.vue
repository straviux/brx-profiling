<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { debounce } from "lodash";
import { router } from "@inertiajs/vue3";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useStorage } from "@vueuse/core";
import { ref, onMounted, computed, watch } from "vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TabLink from "@/Components/TabLink.vue";
import VueMultiselect from "vue-multiselect";
import { DynamicHeroicon } from 'vue-dynamic-heroicons';
import VueSelect from "vue3-select-component";
import {
    MagnifyingGlassIcon,
    PencilSquareIcon,
    EyeIcon,
    UserPlusIcon,
} from "@heroicons/vue/20/solid";

import EditModal from "@/Pages/Admin/VoterProfiles/Modal/EditModal.vue";
import EditDownlineModal from "@/Pages/Admin/VoterProfiles/Modal/AddDownLineModal.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    editdownline: String,
    q: Object,
    profile: Object,
    showAddDownlineModal: Boolean,
    municipalities: Array,
    barangays: Array,
    precincts: Array,
    voters: [Object, Array],
    search_count: [String, Number],
    total_count: [String, Number],
});

const municipalityOptions = ref([]);

const barangayOptions = computed(() =>
    props.barangays?.map((bgy) => ({
        label: bgy,
        value: bgy,
    }))
);
const precinctOptions = computed(() =>
    props.precincts?.map((p) => ({
        label: p,
        value: p,
    }))
);
const filterBarangayQuery = ref(props.q?.filterbarangay?.toUpperCase());
const filterMunicipalityQuery = ref(props.q?.filtermunicipality?.toUpperCase());
const searchNameQuery = ref(props.q?.searchname?.toUpperCase());
const precinctNoQuery = ref(props.q?.filterprecinct?.toUpperCase());
const showResultCount = ref(props.q?.results);
const list_year = ref(2025);

const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

const generateString = (length) => {
    let result = ' ';
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}

const showFilterModal = ref(false);
const closeModal = () => {
    showFilterModal.value = false;
};

watch(
    showResultCount,
    debounce(() =>
        router.get(
            "",
            { results: showResultCount.value },
            { preserveState: true, preserveScroll: true, replace: true }
        )
    )
);

watch(
    list_year,
    debounce(() =>
        router.get(
            "",
            { list_year: list_year.value, },
            { preserveState: true, preserveScroll: true, replace: true }
        )
    )
);

watch(
    searchNameQuery,
    debounce(
        () =>
            router.get(
                "",
                {
                    searchname: searchNameQuery.value, list_year: list_year.value
                    // filtermunicipality: filterMunicipalityQuery.value?.toLowerCase(),
                    // filterbarangay: filterBarangayQuery.value?.toLowerCase(),
                },
                { preserveState: true, preserveScroll: true, replace: true },

            ),
        500
    )
);

watch(
    filterMunicipalityQuery,
    debounce(() => {
        precinctNoQuery.value = null;
        filterBarangayQuery.value = null;
        router.get(
            "",
            {
                searchname: searchNameQuery.value,
                filtermunicipality: filterMunicipalityQuery.value?.toLowerCase(),
                list_year: list_year.value,
                filterbarangay: null,
                filterprecinct: null,
            },
            { preserveState: true, preserveScroll: true, replace: true }
        );
        // console.log(props.barangays);
        // barangayOptions.value = props.barangays.map((bgy) => ({
        //     label: bgy.barangay_name,
        //     value: bgy.barangay_name,
        // }));
    }, 300)
);


watch(
    filterBarangayQuery,
    debounce(() => {
        precinctNoQuery.value = null;
        router.get(
            "",
            {
                searchname: searchNameQuery.value,
                filtermunicipality: filterMunicipalityQuery.value?.toLowerCase(),
                list_year: list_year.value,
                filterbarangay: filterBarangayQuery.value?.toLowerCase(),
                filterprecinct: null,
            },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 300)
);


watch(
    precinctNoQuery,
    debounce(() => {
        router.get(
            "",
            {
                searchname: searchNameQuery.value,
                filtermunicipality: filterMunicipalityQuery.value?.toLowerCase(),
                filterbarangay: filterBarangayQuery.value?.toLowerCase(),
                list_year: list_year.value,
                filterprecinct: precinctNoQuery.value,
            },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 300)
);

// console.log(props);
onMounted(() => {
    // console.log(props.barangays);


    municipalityOptions.value = props.municipalities.map((mun) => ({
        label: mun,
        value: mun,
    }));

});
</script>

<template>

    <Head title="Voters List" />

    <AdminLayout>
        <template #header>Voter's List</template>

        <div class="max-w-full mx-auto py-2">
            <div class="mt-2">
                <div class=" gap-4 mb-4 hidden md:flex">

                    <div class="flex gap-4 w-full">
                        <div class="w-auto space-x-2">
                            <label class="text-sm text-gray-600 font-semibold">Voters List Year</label>
                            <select class="py-0 rounded-sm text-gray-500 border-gray-400" v-model="list_year">
                                <option value="2024">2024</option>
                                <option selected value="2025">2025</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 md:mt-10">
                    <div class="md:flex justify-between items-baseline lg:gap-2 mb-8">
                        <div class="flex justify-between items-center">
                            <div class="hidden md:block w-1/2 md:w-[220px] lg:w-[170px] space-x-2">
                                <label class="text-sm text-gray-500">Show results</label>
                                <select class="py-0 rounded-sm text-gray-500 border-gray-400" v-model="showResultCount">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option selected value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                            </div>
                            <div>
                                <div class="flex md:hidden gap-14 text-xs md:text-normal mx-auto">
                                    <div class="flex flex-col items-center justify-center">
                                        <DynamicHeroicon name="searchCircle" :outline="true" :size="6"
                                            class="text-indigo-700" />
                                        <p class="text-indigo-700">{{ voters.meta?.total || 0 }}</p>
                                        <p class="flex gap-x-1"><span v-if="search_count > 1"> records</span>
                                            <span v-else> record</span>
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-center justify-center">
                                        <DynamicHeroicon name="userGroup" :outline="true" :size="6"
                                            class="text-indigo-700" />
                                        <p class="text-indigo-700">{{ total_count }}</p>
                                        <p>voters</p>
                                    </div>
                                    <div>
                                        <button @click="
                                            showFilterModal = !showFilterModal
                                            "
                                            class="flex items-center justify-center border rounded-lg shadow-lg px-6 py-3 text-normal gap-1 bg-purple-500 text-white ml-8 -mr-6">
                                            <DynamicHeroicon name="filter" :outline="true" :size="5" />
                                            FILTER
                                        </button>
                                    </div>
                                    <!-- <div class="flex flex-col items-center justify-center">
                                    <DynamicHeroicon name="filter" :outline="true" :size="6" class="text-indigo-700" />
                                    #filter
                                </div> -->

                                </div>
                                <div class="text-gray-500 italic border-l-2 ml-2 pl-2 hidden md:block">
                                    <span class="text-gray-600 font-semibold">
                                        {{ voters.meta?.total }}
                                    </span>
                                    <span v-if="search_count > 1"> records</span>
                                    <span v-else> record</span> found
                                </div>
                                <div class="text-gray-500 italic  hidden md:block">
                                    &nbsp;( from
                                    <span class="text-gray-600 font-semibold">
                                        {{ total_count }}
                                    </span>
                                    total voters )
                                </div>
                            </div>
                        </div>
                        <Pagination v-if="voters.data && voters.data.length > 10" :links="voters.meta.links"
                            class="w-auto mt-8 md:mt-0" />
                    </div>
                </div>
                <div class="p-2 border md:hidden rounded-sm shadow-lg">
                    <div class="relative flex items-center text-gray-400 focus-within:text-sky-500">

                        <input type="search" name="leadingIcon" v-model="searchNameQuery" id="searchName"
                            placeholder="Search Name"
                            class="w-full rounded-sm text-normal text-gray-600 outline-none border border-gray-300 focus:border-blue-300 transition" />
                    </div>
                    <div
                        class="flex justify-between align-middle items-center border-b pb-2 mt-4 text-xs md:text-normal">
                        <div class="flex gap-x-1">
                            <p class="text-gray-600 font-semibold">Mun:</p>
                            <p class="font-bold">{{ filterMunicipalityQuery }}</p>
                        </div>
                        <div class="flex gap-x-1">
                            <p class="text-gray-600 font-semibold">Bgy:</p>
                            <p class="font-bold">{{ filterBarangayQuery }}</p>
                        </div>
                        <div class="flex gap-x-1" v-if="precinctNoQuery">
                            <p class="text-gray-600 font-semibold">Precinct:</p>
                            <p class="font-bold">{{ precinctNoQuery }}</p>
                        </div>
                    </div>

                    <div v-if="voters.data && voters.data.length" v-for="(voter, index) in voters.data"
                        :key="'voter_' + voter.pro_voter_id + generateString(8)" class="px-2 py-3 border-b flex gap-2">
                        <p class="text-gray-600 w-[30px]">{{ index + 1 }}. </p>
                        <div class="w-full flex justify-between">
                            <p class="pr-2">{{ voter.voter_name }}</p>
                            <p class="font-semibold">{{ voter.precinct_no }}</p>
                        </div>
                    </div>

                    <!-- <hr class="my-2 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" /> -->
                </div>
                <Table class="border-collapse border border-slate-400 hidden md:block">
                    <template #header>
                        <TableRow>
                            <TableHeaderCell class="w-[10px]">#</TableHeaderCell>
                            <TableHeaderCell>Name</TableHeaderCell>
                            <TableHeaderCell class="w-[340px]">Municipality</TableHeaderCell>
                            <TableHeaderCell class="w-[340px]">Barangay</TableHeaderCell>
                            <TableHeaderCell>Precinct #</TableHeaderCell>
                        </TableRow>
                        <TableRow>
                            <TableDataCell class="px-6 w-[10px] border-collapse border-t border-slate-400">
                            </TableDataCell>
                            <TableDataCell class="border-collapse border-t border-l border-slate-400 px-2">
                                <div class="relative flex items-center text-gray-400 focus-within:text-sky-500">

                                    <input type="search" name="leadingIcon" v-model="searchNameQuery" id="searchName"
                                        placeholder="Search Name"
                                        class="w-full rounded-sm text-lg text-gray-600 outline-none border border-gray-300 focus:border-blue-300 transition" />
                                </div>
                            </TableDataCell>

                            <TableDataCell class="border-collapse border-t border-l border-slate-400 px-2">
                                <VueSelect v-model="filterMunicipalityQuery" placeholder="Select Municipality"
                                    :options="municipalityOptions" />
                            </TableDataCell>
                            <TableDataCell class="border-collapse border-t border-l border-slate-400 px-2">
                                <VueSelect :is-disabled="!filterMunicipalityQuery" v-model="filterBarangayQuery"
                                    placeholder="Select Barangay" :options="barangayOptions" />
                            </TableDataCell>
                            <TableDataCell class="border-collapse border-t border-l border-slate-400 px-2">
                                <VueSelect :is-disabled="!filterBarangayQuery" v-model="precinctNoQuery"
                                    :options="precinctOptions" placeholder="Select Precinct#" />
                            </TableDataCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow v-if="voters.data && voters.data.length" v-for="(voter, index) in voters.data"
                            :key="'voter_' + voter.pro_voter_id + generateString(8)">
                            <TableDataCell
                                class="px-6 w-[10px] border-collapse border-t border-slate-400 text-gray-600 text-normal ">
                                {{ index
                                    +
                                    1
                                }}</TableDataCell>
                            <TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-4 text-gray-700 text-lg">
                                {{
                                    voter.voter_name }}</TableDataCell>

                            <TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-4 text-gray-700 text-lg">
                                {{
                                    voter.municipality_name }}</TableDataCell>
                            <TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-4 text-gray-700 text-lg">
                                {{
                                    voter.barangay_name }}</TableDataCell>
                            <TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-4 text-gray-700 text-lg">
                                {{
                                    voter.precinct_no }}</TableDataCell>
                        </TableRow>
                        <TableRow v-else>
                            <TableDataCell
                                class="px-6 py-8 w-[10px] border-collapse border-t border-slate-400 text-center"
                                colspan="6">No data to be displayed</TableDataCell>
                        </TableRow>
                    </template>
                </Table>
                <div class="mt-8">
                    <div class="hidden md:flex justify-between items-baseline gap-2 mb-8"
                        v-if="voters.data && voters.data.length > 10">
                        <div class="flex">
                            <div class="w-[170px] space-x-2">
                                <label class="text-sm text-gray-500">Show results</label>
                                <select class="py-0 rounded-sm text-gray-500 border-gray-400" v-model="showResultCount">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option selected value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                            </div>
                            <div class="text-gray-500 italic border-l-2 ml-2 pl-2">
                                <span class="text-gray-600 font-semibold">
                                    {{ voters.meta.total }}
                                </span>
                                <span v-if="search_count > 1"> records</span>
                                <span v-else> record</span> found
                            </div>
                            <div class="text-gray-500 italic">
                                &nbsp;( from
                                <span class="text-gray-600 font-semibold">
                                    {{ total_count }}
                                </span>
                                total voters )
                            </div>
                        </div>
                        <Pagination :links="voters.meta.links" class="w-auto mt-8 md:mt-0" />
                    </div>
                </div>
                <!-- {{ voters }} -->
            </div>
        </div>

        <Modal marginTop="md" maxWidth="lg" :show="showFilterModal" @close="closeModal">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-slate-800">
                    Filter Options
                </h2>

                <div class="mt-6 flex justify-between">
                    <div class="flex items-center gap-1">
                        <label class="text-xs text-gray-500">Show Results</label>
                        <select class="py-0 rounded-sm text-gray-500 text-sm border-gray-400" v-model="showResultCount">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option selected value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </div>

                    <div class="flex gap-1 items-center">
                        <label class="text-xs text-gray-5">Voters List</label>
                        <select class="py-0 rounded-sm text-gray-500 text-sm border-gray-400" v-model="list_year">
                            <option value="2024">2024</option>
                            <option selected value="2025">2025</option>
                        </select>
                    </div>
                </div>
                <div class="mt-8">
                    <VueSelect v-model="filterMunicipalityQuery" placeholder="Select Municipality"
                        :options="municipalityOptions" />

                </div>
                <div class="mt-6">
                    <VueSelect :is-disabled="!filterMunicipalityQuery" v-model="filterBarangayQuery"
                        placeholder="Select Barangay" :options="barangayOptions" />

                </div>

                <div class="mt-6">
                    <VueSelect :is-disabled="!filterBarangayQuery" v-model="precinctNoQuery" :options="precinctOptions"
                        placeholder="Select Precinct#" />

                </div>

                <div class="mt-16 flex space-x-4 justify-end">
                    <SecondaryButton @click="closeModal">close</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>
<!-- <style src="vue-multiselect/dist/vue-multiselect.css"></style> -->
