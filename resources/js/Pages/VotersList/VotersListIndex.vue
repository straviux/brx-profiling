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
    barangays: Array,
    precincts: Array,
    voters: [Object, Array],
});
const barangayOptions = ref([]);
const precinctOptions = computed(() =>
    props.precincts?.map((p) => ({
        label: p.precinct_no,
        value: p.precinct_no,
    }))
);
const filterBarangayQuery = ref(props.q?.filterbarangay?.toUpperCase());
const searchNameQuery = ref(props.q?.searchname?.toUpperCase());
const precinctNoQuery = ref(props.q?.filterprecinct?.toUpperCase());
const showResultCount = ref(props.q?.results);

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
    searchNameQuery,
    debounce(
        () =>
            router.get(
                "",
                {
                    searchname: searchNameQuery.value,
                    filterbarangay: filterBarangayQuery.value?.toLowerCase(),
                },
                { preserveState: true, preserveScroll: true, replace: true }
            ),
        500
    )
);

watch(
    filterBarangayQuery,
    debounce(() => {
        precinctNoQuery.value = null;
        router.get(
            "",
            {
                searchname: searchNameQuery.value,
                filterbarangay: filterBarangayQuery.value?.toLowerCase(),
                filterprecinct: null,
            },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 500)
);

watch(
    precinctNoQuery,
    debounce(() => {
        router.get(
            "",
            {
                searchname: searchNameQuery.value,
                filterbarangay: filterBarangayQuery.value?.toLowerCase(),
                filterprecinct: precinctNoQuery.value,
            },
            { preserveState: true, preserveScroll: true, replace: true }
        );
    }, 500)
);

console.log(props);
onMounted(() => {
    // console.log(props.voterprofiles);
    barangayOptions.value = props.barangays.map((bgy) => ({
        label: bgy.barangay_name,
        value: bgy.barangay_name,
    }));
});
</script>

<template>
    <Head title="Voters List" />

    <AdminLayout>
        <template #header>Voter's List</template>

        <div class="max-w-full mx-auto py-2">
            <div class="mt-2">
                <div class="flex gap-4 mb-4">
                    <!--search bar -->
                    <div class="flex gap-4 w-full">
                        <div class="w-[400px]">
                            <div
                                class="relative flex items-center text-gray-400 focus-within:text-sky-500"
                            >
                                <span
                                    class="absolute left-4 flex items-center pr-3 border-r border-gray-300"
                                >
                                    <MagnifyingGlassIcon class="h-4 w-4" />
                                </span>
                                <input
                                    type="search"
                                    name="leadingIcon"
                                    v-model="searchNameQuery"
                                    id="searchName"
                                    placeholder="Search Name"
                                    class="w-full pl-14 pr-4 rounded text-sm text-gray-600 outline-none border border-gray-300 focus:border-blue-300 transition"
                                />
                            </div>
                        </div>

                        <div class="w-[340px] flex items-center rounded-lg">
                            <VueSelect
                                v-model="filterBarangayQuery"
                                placeholder="Select Barangay"
                                :options="barangayOptions"
                            />
                        </div>
                        <div class="w-[220px] flex items-center">
                            <VueSelect
                                :is-disabled="!filterBarangayQuery"
                                v-model="precinctNoQuery"
                                :options="precinctOptions"
                                placeholder="Select Precinct#"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-baseline gap-2 w-[200px] mb-6">
                    <label for="" class="text-sm">Show results</label>
                    <select
                        name=""
                        id=""
                        class="py-0 rounded-sm"
                        v-model="showResultCount"
                    >
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option selected value="100">100</option>
                        <option value="200">200</option>
                    </select>
                </div>
                <Table class="border-collapse border border-slate-400">
                    <template #header>
                        <TableRow>
                            <TableHeaderCell class="w-[10px]"
                                >#</TableHeaderCell
                            >
                            <TableHeaderCell>Name</TableHeaderCell>
                            <TableHeaderCell>Municipality</TableHeaderCell>
                            <TableHeaderCell>Barangay</TableHeaderCell>
                            <TableHeaderCell>Precinct #</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow
                            v-if="voters.data.length"
                            v-for="(voter, index) in voters.data"
                            :key="'voter_' + voter.id"
                        >
                            <TableDataCell
                                class="px-6 w-[10px] border-collapse border-t border-slate-400"
                                >{{ index + 1 }}</TableDataCell
                            >
                            <TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-1"
                                >{{ voter.voter_name }}</TableDataCell
                            >

                            <TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-1"
                                >{{ voter.municipality_name }}</TableDataCell
                            ><TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-1"
                                >{{ voter.barangay_name }}</TableDataCell
                            >
                            <TableDataCell
                                class="border-collapse border-t border-l border-slate-400 indent-1"
                                >{{ voter.precinct_no }}</TableDataCell
                            >
                        </TableRow>
                        <TableRow v-else
                            ><TableDataCell
                                class="px-6 py-8 w-[10px] border-collapse border-t border-slate-400 text-center"
                                colspan="6"
                                >No data to be displayed</TableDataCell
                            ></TableRow
                        >
                    </template>
                </Table>
                <Pagination class="mt-4" :links="voters.meta.links" />
                <!-- {{ voters }} -->
            </div>
        </div>
    </AdminLayout>
</template>
<!-- <style src="vue-multiselect/dist/vue-multiselect.css"></style> -->
