<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { debounce } from 'lodash';
import { router } from '@inertiajs/vue3';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useStorage } from '@vueuse/core';
import { ref, onMounted, computed, watch } from 'vue';
import { usePermission } from '@/composable/permissions';
import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TabLink from '@/Components/TabLink.vue';
import VueMultiselect from 'vue-multiselect';
import VueSelect from 'vue3-select-component';
import {
	MagnifyingGlassIcon,
	PencilSquareIcon,
	TrashIcon,
	IdentificationIcon,
	UserPlusIcon,
} from '@heroicons/vue/20/solid';

import CreateModal from '@/Pages/Admin/ChristianCommunity/Modal/CreateModal.vue';
import EditModal from '@/Pages/Admin/ChristianCommunity/Modal/EditModal.vue';
import Pagination from '@/Components/Pagination.vue';

const { hasPermission } = usePermission();
const sortColumnBy = (columnName) => {
	// console.log(sortColumsDirection.value.name);
	console.log('test');
};
const props = defineProps({
	action: String,
	q: Object,
	profile: Object,
	municipalities: Array,
	barangays: Array,
	precincts: Array,
	voterprofiles: [Object, Array],
	search_count: [String, Number],
	total_count: [String, Number],
	voters: [Object, Array],
	coordinators: [Object, Array],
	leaders: [Object, Array],
	subleaders: [Object, Array]
});
// const barangayOptions = ref([]);
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
const gridview = useStorage('gridview', false);
const form = useForm({});
const currentVoterPosition = route().params.position || null;
const showConfirmDeleteVoterProfileModal = ref(false);
const modalVoterProfileData = ref({ id: null, name: null });

const filterMunicipalityQuery = ref(props.q?.filtermunicipality?.toUpperCase());
const filterBarangayQuery = ref(props.q?.filterbarangay?.toUpperCase());
const searchNameQuery = ref(props.q?.searchname?.toUpperCase());
const precinctNoQuery = ref(props.q?.filterprecinct?.toUpperCase());
const showResultCount = ref(props.q?.results);

const confirmDeleteVoterProfile = (profileID, profileName) => {
	showConfirmDeleteVoterProfileModal.value = true;
	modalVoterProfileData.value.id = profileID;
	modalVoterProfileData.value.name = profileName;
};
const closeModal = () => {
	showConfirmDeleteVoterProfileModal.value = false;
};
const deleteProfile = (voterProfileID) => {
	// console.log(voterProfileID);
	form.delete(route('christiancommunity.destroy', voterProfileID), {
		onSuccess: () => closeModal(),
	});
};


watch(
	showResultCount,
	debounce(() =>
		router.get(
			'',
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
				'',
				{
					searchname: searchNameQuery.value,
				},
				{ preserveState: true, preserveScroll: true, replace: true }
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
			'',
			{
				filtermunicipality: filterMunicipalityQuery.value?.toLowerCase(),
			},
			{ preserveState: true, preserveScroll: true, replace: true }
		);
	}, 500)
);

watch(
	filterBarangayQuery,
	debounce(() => {
		precinctNoQuery.value = null;
		router.get(
			'',
			{
				filterbarangay: filterBarangayQuery.value?.toLowerCase(),
			},
			{ preserveState: true, preserveScroll: true, replace: true }
		);
	}, 500)
);

watch(
	precinctNoQuery,
	debounce(() => {
		router.get(
			'',
			{
				filterprecinct: precinctNoQuery.value,
			},
			{ preserveState: true, preserveScroll: true, replace: true }
		);
	}, 500)
);

watch(props, () => {
	console.log(props);
});


onMounted(() => {
	// console.log(props.voterprofiles);
	console.log(props);
	municipalityOptions.value = props.municipalities.map((bgy) => ({
		label: bgy,
		value: bgy,
	}));
	// console.log(props.precincts);
});
</script>

<template>

	<Head title="Voter's Profile" />

	<AdminLayout>
		<template #header>Christian Community Voter's Profile</template>

		<div class="max-w-full mx-auto py-4">
			<div
				class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 flex justify-center items-center">
				<ul class="flex flex-wrap -mb-px">
					<li class="me-2">
						<TabLink :href="route('christiancommunity.showposition', 'all')"
							:active="route().current('christiancommunity.showposition', 'all')">All</TabLink>
					</li>
					<li class="me-2">
						<TabLink :href="route('christiancommunity.showposition', 'coordinator')"
							:active="route().current('christiancommunity.showposition', 'coordinator')">Coordinator
						</TabLink>
					</li>
					<li class="me-2">
						<TabLink :href="route('christiancommunity.showposition', 'leader')"
							:active="route().current('christiancommunity.showposition', 'leader')">Leader</TabLink>
					</li>
					<li class="me-2">
						<TabLink :href="route('christiancommunity.showposition', 'subleader')"
							:active="route().current('christiancommunity.showposition', 'subleader')">SubLeader
						</TabLink>
					</li>
					<li>
						<TabLink :href="route('christiancommunity.showposition', 'member')"
							:active="route().current('christiancommunity.showposition', 'member')">Member</TabLink>
					</li>
				</ul>

				<Link v-if="hasPermission('create voterprofile')" :href="route('christiancommunity.showposition', {
					position: currentVoterPosition,
					action: 'create'
				})" class=" text-emerald-500 underline font-bold px-3 py-2 bg-none rounded ml-auto flex items-center justify-center
					gap-1 text-lg">
				<UserPlusIcon class="h-6 w-6" />New Profile</Link>
			</div>

			<div class="flex gap-4 mt-12 mb-4">
				<!--search bar -->
				<div class="flex gap-4 w-full">
					<div class="w-[400px]">
						<div class="relative flex items-center text-gray-400 focus-within:text-sky-500">
							<span class="absolute left-4 h-6 flex items-center pr-3 border-r border-gray-300">
								<MagnifyingGlassIcon class="h-5 w-5" />
							</span>
							<input type="search" name="leadingIcon" v-model="searchNameQuery" id="searchName"
								placeholder="Search Name"
								class="w-full pl-14 pr-4 rounded text-sm text-gray-600 outline-none border border-gray-300 focus:border-blue-300 transition" />
						</div>
					</div>
					<div class="w-[340px] flex items-center rounded-lg">

						<VueSelect v-model="filterMunicipalityQuery" placeholder="Select Municipality"
							:options="municipalityOptions" />
					</div>

					<div class="w-[340px] flex items-center rounded-lg">
						<VueSelect v-model="filterBarangayQuery" placeholder="Select Barangay"
							:options="barangayOptions" />
					</div>
					<div class="w-[220px] flex items-center">
						<VueSelect :is-disabled="!filterBarangayQuery" v-model="precinctNoQuery"
							:options="precinctOptions" placeholder="Select Precinct#" />
					</div>
				</div>
				<div class="flex items-center gap-2 ml-auto px-2 mr-2" v-if="currentVoterPosition != 'all'">
					<label class="relative inline-flex cursor-pointer items-center">
						<input id="switch" type="checkbox" class="peer sr-only" v-model="gridview" />
						<label for="switch" class="hidden"></label>
						<div
							class="peer h-6 w-11 rounded-full border bg-slate-200 after:absolute after:left-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-slate-800 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:ring-green-300">
						</div>
					</label>
					<p class="text-xs text-gray-600" v-if="gridview">Grid</p>
					<p class="text-xs text-gray-600" v-else>Table</p>
				</div>
			</div>

			<div class="mt-10">
				<div class="flex justify-between items-baseline gap-2 mb-8" v-if="voterprofiles.data.length > 10">
					<div class="flex flex-row">
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
								{{ search_count }}
							</span>
							<span v-if="search_count > 1"> records</span>
							<span v-else> record</span> found
						</div>
						<div class="text-gray-500 italic">
							( from
							<span class="text-gray-600 font-semibold">
								{{ total_count }}
							</span>
							total voters)
						</div>
					</div>
					<Pagination :links="voterprofiles.meta.links" v-if="voterprofiles.data.length" />
				</div>
				<ul v-if="gridview && currentVoterPosition !== 'all'" role="list"
					class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
					<li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow border border-gray-300 transition hover:-translate-y-2 duration-150 hover:shadow-lg"
						v-for="(profile, index) in voterprofiles.data" :key="'profile_' + profile.id">
						<div class="flex w-full items-start justify-between space-x-6 px-2 py-2.5">
							<p class="text-gray-400 text-xs absolute mt-1">#{{ index + 1 }}</p>
							<div class="flex-1">
								<div class="flex items-center space-x-3">
									<h3 class="text-sm font-medium text-gray-600">
										{{ profile.name }}
									</h3>
								</div>
								<p class="mt-1 font-semibold text-[12px] text-gray-500">
									{{ profile.barangay }} -
									{{ profile.precinct_no }}
								</p>
							</div>

						</div>
						<div class="px-6 py-2" v-if="currentVoterPosition == 'coordinator'">
							<h3 class="text-gray-600 ml-4">Leaders</h3>
							<div class="mt-1">
								<div class="tree-view text-sm">
									<ul class="list-none pl-5">
										<li class="py-1" v-for="(leader, i) in profile.members" :key="'_leader_' + i">
											<span class="text-gray-500 text-[11px]">{{ i + 1 }}. </span>
											<span class="text-gray-700 tracking-wide text-[12px]">
												{{ leader.name }}</span>
										</li>
										<li class="py-1 text-gray-500 text-[11px]" v-if="profile.members.length < 7"
											v-for="index in 7 - profile.members.length">
											{{ profile.members.length + index }}. N/A
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="px-6 py-2" v-else-if="currentVoterPosition == 'leader'">
							<h3 class="text-gray-600 ml-4">SubLeaders</h3>
							<div class="mt-1">
								<div class="tree-view text-sm">
									<ul class="list-none pl-5">
										<li class="py-1" v-for="(member, i) in profile.members" :key="'_member_' + i">
											<span class="text-gray-500 text-[11px]">{{ i + 1 }}. </span>
											<span class="text-gray-700 tracking-wide text-[12px]">
												{{ member.name }}</span>
										</li>
										<li class="py-1 text-gray-500 text-[11px]" v-if="profile.members.length < 3"
											v-for="index in 3 - profile.members.length">
											{{ profile.members.length + index }}. N/A
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="px-6 py-2" v-else-if="currentVoterPosition == 'subleader'">
							<h3 class="text-gray-600 ml-4">Members</h3>
							<div class="mt-1">
								<div class="tree-view text-sm">
									<ul class="list-none pl-5">
										<li class="py-1" v-for="(member, i) in profile.members" :key="'_member_' + i">
											<span class="text-gray-500 text-[11px]">{{ i + 1 }}. </span>
											<span class="text-gray-700 tracking-wide text-[12px]">
												{{ member.name }}</span>
										</li>
										<li class="py-1 text-gray-500 text-[11px]" v-if="profile.members.length < 3"
											v-for="index in 3 - profile.members.length">
											{{ profile.members.length + index }}. N/A
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="px-4 py-2 flex" v-else-if="currentVoterPosition == 'member'">
							<p v-if="!profile.leader" class="text-sm text-red-400 font-semibold animate-pulse mx-auto">
								no
								leader
								assigned</p>
							<div v-else class="flex">
								<p class="text-gray-600 ml-4 text-[12px]">Leader:</p>
								<p class="ml-2 text-[12px] text-gray-500">
									{{ profile.leader?.name }}

								</p>
							</div>
						</div>
						<div>
							<div class="-mt-px flex divide-x divide-gray-200">
								<div v-if="hasPermission('delete voterprofile')"
									class="flex w-0 flex-1 text-red-400 hover:text-red-600">
									<button
										class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-1 rounded-bl-lg border border-transparent py-4 text-xs font-semibold"
										@click="confirmDeleteVoterProfile(profile.id, profile.name)">
										<TrashIcon class="h-5 w-5 text-red-400" />
										Delete
									</button>
								</div>
								<div class="flex w-0 flex-1 text-blue-400 hover:text-blue-600">
									<Link v-if="hasPermission('edit voterprofile')" :href="route('christiancommunity.showposition', {
										position: currentVoterPosition,
										id: profile.id,
										action: 'edit'
									})
										" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-1 rounded-bl-lg border border-transparent py-4 text-xs font-semibold"
										preserve-state preserve-scroll>
									<PencilSquareIcon class="h-5 w-5" />
									Edit
									</Link>
								</div>

								<div class="-ml-px flex w-0 flex-1 text-purple-500 hover:text-purple-600">
									<Link :href="route('christiancommunity.viewprofile', {
										id: profile.id,
									})
										" class="relative inline-flex w-0 flex-1 items-center justify-center gap-x-1 rounded-br-lg border border-transparent py-4 text-xs font-semibold">
									<IdentificationIcon class="h-5 w-5" />
									Downline
									</Link>
								</div>
							</div>
						</div>
					</li>
				</ul>

				<Table class="border-collapse border border-slate-400" v-else>
					<template #header>
						<TableRow>
							<TableHeaderCell class="w-[10px]">#</TableHeaderCell>
							<TableHeaderCell @click="sortColumnBy('name')">Name</TableHeaderCell>
							<TableHeaderCell @click="sortColumnBy('position')">Position</TableHeaderCell>
							<TableHeaderCell @click="sortColumnBy('municipality')">Municipality</TableHeaderCell>
							<TableHeaderCell @click="sortColumnBy('barangay')">Barangay</TableHeaderCell>
							<TableHeaderCell @click="sortColumnBy('precinct_no')">Precinct #</TableHeaderCell>
							<TableHeaderCell class="w-[160px]">Action</TableHeaderCell>
						</TableRow>
					</template>
					<template #default>
						<TableRow v-if="voterprofiles.data.length" v-for="(voter, index) in voterprofiles.data"
							:key="'voter_' + voter.id" class="hover:bg-gray-200">
							<TableDataCell class="px-6 w-[10px] border-collapse border-t border-slate-400">{{
								index + 1
							}}</TableDataCell>
							<TableDataCell class="border-collapse border-t border-l border-slate-400 indent-1">
								<div>
									{{ voter.lastname + ', ' + voter.firstname + ' ' + (voter.middlename || '') }}
								</div>
							</TableDataCell>
							<TableDataCell class="border-collapse border-t border-l border-slate-400 indent-1">{{
								voter.position
							}}</TableDataCell>
							<TableDataCell class="border-collapse border-t border-l border-slate-400 indent-1">{{
								voter.municipality
							}}</TableDataCell>
							<TableDataCell class="border-collapse border-t border-l border-slate-400 indent-1">{{
								voter.barangay
							}}</TableDataCell>
							<TableDataCell class="border-collapse border-t border-l border-slate-400 indent-1">{{
								voter.precinct_no
							}}</TableDataCell>
							<TableDataCell class="border-collapse border-t border-l border-slate-400">
								<div class="flex space-x-6 justify-center">
									<Link v-if="hasPermission('edit voterprofile')" :href="route('christiancommunity.showposition', {
										position: currentVoterPosition,
										action: 'edit',
										id: voter.id,
									})
										" preserve-state preserve-scroll class="text-blue-400 hover:text-blue-600 flex">
									<PencilSquareIcon class="h-5 w-5 text-blue-400" />
									Edit
									</Link>

									<button v-if="hasPermission('delete voterprofile')"
										class="text-red-500 hover:text-red-600 flex"
										@click="confirmDeleteVoterProfile(voter.id, voter.name)">
										<TrashIcon class="h-5 w-5 text-red-400" />
										Delete
									</button>
								</div>
							</TableDataCell>
						</TableRow>
						<TableRow v-else>
							<TableDataCell
								class="px-6 py-8 w-[10px] border-collapse border-t border-slate-400 text-center"
								colspan="7">No data to be displayed</TableDataCell>
						</TableRow>
					</template>
				</Table>
				<div class="flex justify-between items-baseline gap-2 mb-6 mt-8">
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
								{{ search_count }}
							</span>
							<span v-if="search_count > 1"> records</span>
							<span v-else> record</span> found
						</div>
						<div class="text-gray-500 italic">
							( from
							<span class="text-gray-600 font-semibold">
								{{ total_count }}
							</span>
							total voters)
						</div>
					</div>
					<Pagination :links="voterprofiles.meta.links" v-if="voterprofiles.data.length" />
				</div>
				<EditModal v-if="props.action == 'edit' && hasPermission('edit voterprofile')" :profile="props.profile"
					:barangays="barangayOptions" :position="currentVoterPosition" />
				<CreateModal v-if="props.action == 'create' && hasPermission('create voterprofile')"
					:municipalities="municipalityOptions" :barangays="barangayOptions"
					:coordinators="props.coordinators" :leaders="props.leaders" :subleaders="props.subleaders"
					:position="currentVoterPosition" :action="props.action" />

				<!-- <EditDownlineModal v-if="props.q.showdownline" /> -->
			</div>
		</div>
		<Modal v-if="hasPermission('delete voterprofile')" maxWidth="lg" :show="showConfirmDeleteVoterProfileModal"
			@close="closeModal">
			<div class="p-6">
				<h2 class="text-lg font-semibold text-slate-800">
					Are you sure you want to delete this profile?
				</h2>
				<p class="mt-4 bg-slate-100 p-2 text-center text-red-700 font-semibold">
					"{{ modalVoterProfileData.name }}"
				</p>

				<div class="mt-6 flex space-x-4">
					<DangerButton @click="deleteProfile(modalVoterProfileData.id)"> Delete</DangerButton>
					<SecondaryButton @click="closeModal">Cancel</SecondaryButton>
				</div>
			</div>
		</Modal>
	</AdminLayout>
</template>
<style scoped>
/* .multiselect__input {
    min-height: 24px !important;
    font-size: 12px !important;
    border-radius: 8px !important;
}
.multiselect__option {
    font-size: 14px !important;
}

.multiselect__input::placeholder {
    text-transform: none;
} */
#searchName::placeholder {
	color: #888;
	font-weight: 400;
	font-size: 1rem;
}

:deep(.vue-select input) {
	padding: 7px 10px;
}

:deep(.vue-select input::placeholder) {
	color: #888;
	font-weight: 400;
	font-size: 1rem;
}

:deep(.vue-select .focused .menu-option .focused) {
	background: #7dd3fc;
}

:deep(.vue-select .value-container),
:deep(.vue-select .indicators-container) {
	background-color: none;
}

:deep(.vue-select .menu-option:hover) {
	background: #ddd;
}
</style>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
