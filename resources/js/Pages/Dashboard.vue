<script setup>
import { computed } from 'vue'
import { Head, Link } from "@inertiajs/vue3";
import { usePage } from '@inertiajs/vue3'
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { FingerPrintIcon, HomeModernIcon, IdentificationIcon, Squares2X2Icon, UserGroupIcon, UserPlusIcon } from "@heroicons/vue/20/solid";
import { DynamicHeroicon } from 'vue-dynamic-heroicons';

const page = usePage()
const localip = computed(() => page.props.localip)
</script>

<template>

    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>Dashboard</template>
        <div class="py-4">
            <div class="mx-4">

                <h1>Welcome {{ $page.props.auth.user.name }}</h1>
                <div>You can access this site on your local network thru
                    <span class="underline font-semibold font-mono">{{ localip }}:9000</span>
                </div>




                <div class="flex flex-wrap md:-mx-6 mt-8 md:px-6 md:gap-x-12 gap-x-4">
                    <div class="w-full mt-6 md:mb-8">
                        <p class="text-xl text-gray-600 font-semibold">Links</p>
                        <!-- <DynamicHeroicon name="cake" :outline="true" class="text-indigo-700" :size="8" /> -->
                    </div>


                    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 mx-auto">
                        <Link :href="route('dashboard')"
                            class="flex flex-col md:flex-row gap-y-2 items-center py-4 md:py-8 rounded-lg bg-white shadow border border-gray-300 transition hover:-translate-y-2 duration-150 hover:shadow-lg  mt-6 px-0 md:px-6 md:w-auto xl:mt-0">


                        <!-- <Squares2X2Icon class="h-8 w-8 text-slate-600" /> -->
                        <DynamicHeroicon name="viewGrid" class="text-slate-600" :size="9" />


                        <div class="mx-3">
                            <h4 class="text-xs md:text-xl underline underline-offset-8 font-semibold text-gray-600">
                                Dashboard</h4>
                        </div>

                        </Link>

                        <Link :href="route('voterslist.index')"
                            class="flex flex-col md:flex-row gap-y-2 items-center py-4 md:py-8 rounded-lg bg-white shadow border border-gray-300 transition hover:-translate-y-2 duration-150 hover:shadow-lg  mt-6 px-0 md:px-6 md:w-auto xl:mt-0">


                        <DynamicHeroicon name="fingerPrint" class="text-slate-600" :size="9" />


                        <div class="mx-3">
                            <h4 class="text-xs md:text-xl underline underline-offset-8 font-semibold text-gray-600">
                                Voters
                                List</h4>
                        </div>

                        </Link>

                        <Link :href="route('votersprofile.index')"
                            class="flex flex-col md:flex-row gap-y-2 items-center py-4 md:py-8 rounded-lg bg-white shadow border border-gray-300 transition hover:-translate-y-2 duration-150 hover:shadow-lg  mt-6 px-0 md:px-6 md:w-auto xl:mt-0">


                        <DynamicHeroicon name="userGroup" class="text-slate-600" :size="9" />


                        <div class="mx-3">
                            <h4 class="text-xs md:text-xl underline underline-offset-8 font-semibold text-gray-600">
                                Kiel's
                                Profiling
                            </h4>
                        </div>

                        </Link>

                        <Link :href="route('votersprofile.index')"
                            class="flex flex-col md:flex-row gap-y-2 items-center py-4 md:py-8 rounded-lg bg-white shadow border border-gray-300 transition hover:-translate-y-2 duration-150 hover:shadow-lg  mt-6 px-0 md:px-6 md:w-auto xl:mt-0">


                        <DynamicHeroicon name="heart" class="text-slate-600" :size="9" />


                        <div class="mx-3">
                            <h4 class="text-xs md:text-xl underline underline-offset-8 font-semibold text-gray-600">
                                Christian
                                Community
                            </h4>
                        </div>

                        </Link>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-6 mt-6">
                    <div class="w-full mt-6 mb-8 px-6">
                        <p class="text-xl text-gray-600 font-semibold">Stats</p>
                    </div>

                    <div class="w-full px-0 md:px-6 sm:w-1/2 xl:w-1/3">
                        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-slate-100">
                            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                                <UserGroupIcon class="h-8 w-8 text-white" />
                            </div>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">4644</h4>
                                <div class="text-gray-500">Total Voters</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full mt-6 px-0 md:px-6 sm:w-1/2 xl:w-1/3 sm:mt-0">
                        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-slate-100">
                            <div class="p-3 rounded-full bg-orange-600 bg-opacity-75">

                                <IdentificationIcon class="h-8 w-8 text-white" />
                            </div>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">3453</h4>
                                <div class="text-gray-500">Total Profiles</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full mt-6 px-0 md:px-6 sm:w-1/2 xl:w-1/3 xl:mt-0">
                        <div class="flex items-center px-5 py-6 shadow-sm rounded-md bg-slate-100">
                            <div class="p-3 rounded-full bg-pink-600 bg-opacity-75">
                                <UserPlusIcon class="h-8 w-8 text-white" />
                            </div>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">678</h4>
                                <div class="text-gray-500">New Profile</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
