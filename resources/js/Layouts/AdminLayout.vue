<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import SidebarLink from "@/Components/SidebarLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import { usePermission } from "@/composable/permissions";
import { DynamicHeroicon } from 'vue-dynamic-heroicons';
// import ToastList from "@/Components/ToastList.vue";
const showingNavigationDropdown = ref(false);

const { hasRole } = usePermission();
import {
    UsersIcon,
    Squares2X2Icon,
    UserGroupIcon,
    CogIcon,
    FingerPrintIcon,
    ShieldExclamationIcon,
    ArrowRightStartOnRectangleIcon,
    DocumentChartBarIcon,
    HomeModernIcon,
    HeartIcon
} from "@heroicons/vue/20/solid";
// import {  } from "@heroicons/vue/20/solid";
// import { UserGroupIcon } from "@heroicons/vue/20/solid";
// import { CogIcon } from "@heroicons/vue/20/solid";
// import { FingerPrintIcon } from "@heroicons/vue/20/solid";
// import { ArrowRightStartOnRectangleIcon } from "@heroicons/vue/20/solid";
const toggleMenu = ref(false);

</script>

<template>
    <div class="w-full h-full">
        <!-- component -->

        <aside
            class="hidden fixed z-10 top-0 pb-3 px-2 w-full md:flex flex-col justify-between h-screen border-r bg-[#2C3639] transition duration-300 md:w-[12%]"
            :class="{ '!flex': toggleMenu }">

            <div class="block">
                <div class="-mx-6 px-6 py-4">
                    <a href="#" title="home" class="text-2xl font-bold font-mono text-white">
                        <!-- <img
                            src="https://tailus.io/sources/blocks/stats-cards/preview/images/logo.svg"
                            class="w-32"
                            alt="tailus logo"
                        /> -->
                        BRX Profiling
                    </a>
                </div>

                <ul class="space-y-6 md:space-y-4 tracking-wide mt-8 text-normal md:text-sm">
                    <li>
                        <SidebarLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="text-white">
                            <Squares2X2Icon class="h-5 w-5" />

                            <span class="-mr-1 font-medium">Dashboard</span>
                        </SidebarLink>
                    </li>
                    <li>
                        <SidebarLink :href="route('voterslist.index')" :active="route().current('voterslist.index')"
                            class="text-white">
                            <FingerPrintIcon class="h-5 w-5" />
                            <span class="-mr-1 font-medium">Voter's List</span>
                        </SidebarLink>
                    </li>
                    <li>
                        <SidebarLink :href="route('votersprofile.index')" :active="route().current('votersprofile.index') ||
                            route().current('votersprofile.showposition')
                            " class="text-white">
                            <UserGroupIcon class="h-5 w-5" />
                            <span class="-mr-1 font-medium">Kiel's Profiling</span>
                        </SidebarLink>
                    </li>
                    <li>
                        <SidebarLink :href="route('christiancommunity.index')" :active="route().current('christiancommunity.index') ||
                            route().current('christiancommunity.showposition')" class="text-white">
                            <HeartIcon class="h-5 w-5" />
                            <span class="-mr-1 font-medium">Christian Community</span>
                        </SidebarLink>
                    </li>
                    <!-- <li>
                        <SidebarLink :href="route('votersprofile.index')" class=" text-white">
                            <DocumentChartBarIcon class="h-5 w-5" />
                            <span class="-mr-1 font-medium">Reports</span>
                        </SidebarLink>
                    </li> -->
                    <li>
                        <SidebarLink v-if="hasRole('admin')" :href="route('users.index')"
                            :active="route().current('users.index')" class="text-white">
                            <UsersIcon class="h-5 w-5" />
                            <span class="-mr-1 font-medium">Users</span>
                        </SidebarLink>
                    </li>
                    <li>
                        <SidebarLink v-if="hasRole('admin')" :href="route('roles.index')" :active="route().current('roles.index') ||
                            route().current('roles.create')
                            " class="text-white">
                            <CogIcon class="h-5 w-5" />
                            <span class="-mr-1 font-medium">Roles</span>
                        </SidebarLink>
                    </li>
                    <li>
                        <SidebarLink v-if="hasRole('admin')" :href="route('permissions.index')" :active="route().current('permissions.index') ||
                            route().current('permissions.create')
                            " class="text-white">
                            <ShieldExclamationIcon class="h-5 w-5" />
                            <span class="-mr-1 font-medium">Permisions</span>
                        </SidebarLink>
                    </li>
                </ul>
            </div>

            <div class="px-6 -mx-6 pt-4 flex justify-between items-center border-t">
                <Link class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-100 group hover:text-red-400"
                    :href="route('logout')" method="post" as="button">
                <ArrowRightStartOnRectangleIcon class="h-5 w-5" />
                <span>Logout</span>
                </Link>
            </div>
        </aside>

        <div class="ml-auto mb-6 w-full md:w-[88%]">
            <div class="sticky z-10 top-0 h-16 border-b bg-[#2C3639] lg:py-2.5">
                <div class="px-6 flex items-center justify-between space-x-4">
                    <h5 class="text-xl md:text-2xl text-white font-medium lg:block">
                        <slot name="header" />
                    </h5>
                    <button class="w-12 h-16 -mr-2 border-none lg:hidden">
                        <Transition>
                            <DynamicHeroicon :name="toggleMenu ? 'x' : 'menu'"
                                class="text-gray-50 button transition-all" :size="9"
                                @click="toggleMenu = !toggleMenu" />
                        </Transition>
                    </button>
                    <!-- <div v-if="!toggleMenu" class="h-full w-full">test</div> -->
                    <div class="space-x-4 hidden md:flex">
                        <!-- <button aria-label="notification"
                            class="w-10 h-10 rounded-xl border bg-gray-100 focus:bg-gray-100 active:bg-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 m-auto text-gray-600"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                            </svg>
                        </button> -->
                        <Link
                            class="px-2 py-3 flex items-center space-x-2 rounded-md text-gray-100 group hover:text-red-400"
                            :href="route('logout')" method="post" as="button">
                        <ArrowRightStartOnRectangleIcon class="h-4 w-4" />
                        <span>Logout</span>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="px-4 md:px-6 pt-6 min-h-[calc(100vh-6rem)]">
                <!-- <ToastList /> -->
                <slot />
            </div>
        </div>
    </div>
</template>
<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.1s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>