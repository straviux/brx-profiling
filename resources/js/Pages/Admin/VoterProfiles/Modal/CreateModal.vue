<template>
    <div class="inset-0 flex items-center justify-center">
        <button
            type="button"
            @click="openModal"
            class="rounded-md bg-black/20 px-4 py-2 text-sm font-medium text-white hover:bg-black/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
        >
            Open dialog
        </button>
    </div>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
                        >
                            <DialogTitle
                                as="h3"
                                class="text-xl font-medium leading-6 text-gray-900 flex justify-between"
                            >
                                Edit Voter's Profile
                                <button class="-mr-4 -mt-8" @click="closeModal">
                                    <XCircleIcon class="h-8 w-8 text-red-400" />
                                </button>
                            </DialogTitle>
                            <div class="mt-6">
                                <div class="mt-4 flex gap-2">
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="lastname"
                                            value="Last Name"
                                        />

                                        <TextInput
                                            id="lastname"
                                            type="text"
                                            v-model="form.lastname"
                                            autofocus
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="firstname"
                                            value="First Name"
                                        />

                                        <TextInput
                                            id="firstname"
                                            v-model="form.firstname"
                                            type="text"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="middlename"
                                            value="Middle Name"
                                        />

                                        <TextInput
                                            id="middlename"
                                            v-model="form.middlename"
                                            type="text"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="extension"
                                            value="Extension"
                                        />

                                        <TextInput
                                            id="extension"
                                            v-model="form.extension"
                                            type="text"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-2">
                                    <div class="w-1/3 pr-1">
                                        <InputLabel
                                            for="position"
                                            value="Position"
                                        />
                                        <Combobox
                                            id="position"
                                            v-model="form.position"
                                        >
                                            <div class="relative mt-1">
                                                <div
                                                    class="relative w-full cursor-default"
                                                >
                                                    <ComboboxInput
                                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                        :displayValue="
                                                            (position) =>
                                                                position.name
                                                        "
                                                        @change="
                                                            query =
                                                                $event.target
                                                                    .value
                                                        "
                                                    />
                                                    <ComboboxButton
                                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
                                                    >
                                                        <ChevronUpDownIcon
                                                            class="h-5 w-5 text-gray-400"
                                                            aria-hidden="true"
                                                        />
                                                    </ComboboxButton>
                                                </div>
                                                <TransitionRoot
                                                    leave="transition ease-in duration-100"
                                                    leaveFrom="opacity-100"
                                                    leaveTo="opacity-0"
                                                    @after-leave="query = ''"
                                                >
                                                    <ComboboxOptions
                                                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                                                    >
                                                        <div
                                                            v-if="
                                                                filteredPosition.length ===
                                                                    0 &&
                                                                query !== ''
                                                            "
                                                            class="relative cursor-default select-none px-4 py-2 text-gray-700"
                                                        >
                                                            Nothing found.
                                                        </div>

                                                        <ComboboxOption
                                                            v-for="position in filteredPosition"
                                                            as="template"
                                                            :key="position.id"
                                                            :value="position"
                                                            v-slot="{
                                                                selected,
                                                                active,
                                                            }"
                                                        >
                                                            <li
                                                                class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                                :class="{
                                                                    'bg-sky-600 text-white':
                                                                        active,
                                                                    'text-gray-900':
                                                                        !active,
                                                                }"
                                                            >
                                                                <span
                                                                    class="block truncate"
                                                                    :class="{
                                                                        'font-medium':
                                                                            selected,
                                                                        'font-normal':
                                                                            !selected,
                                                                    }"
                                                                >
                                                                    {{
                                                                        position.name
                                                                    }}
                                                                </span>
                                                                <span
                                                                    v-if="
                                                                        selected
                                                                    "
                                                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                                    :class="{
                                                                        'text-white':
                                                                            active,
                                                                        'text-sky-600':
                                                                            !active,
                                                                    }"
                                                                >
                                                                    <CheckIcon
                                                                        class="h-5 w-5"
                                                                        aria-hidden="true"
                                                                    />
                                                                </span>
                                                            </li>
                                                        </ComboboxOption>
                                                    </ComboboxOptions>
                                                </TransitionRoot>
                                            </div>
                                        </Combobox>
                                    </div>
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="purok"
                                            value="Purok/Sitio"
                                        />

                                        <TextInput
                                            id="purok"
                                            v-model="form.purok"
                                            type="text"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="contact_no"
                                            value="Contact Number"
                                        />

                                        <TextInput
                                            id="contact_no"
                                            v-model="form.contact_no"
                                            type="text"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                </div>
                                <div class="mt-4 flex gap-2">
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="precint_no"
                                            value="Precinct Number"
                                        />

                                        <TextInput
                                            id="precint_no"
                                            type="text"
                                            v-model="form.precinct_no"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="birthdate"
                                            value="Birthdate"
                                        />

                                        <TextInput
                                            id="birthdate"
                                            v-model="form.birthdate"
                                            type="date"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                    <div class="w-1/3">
                                        <InputLabel
                                            for="gender"
                                            value="Gender"
                                        />

                                        <div class="flex gap-4 mt-4 ml-4">
                                            <div
                                                class="flex items-center mb-4 cursor-pointer"
                                            >
                                                <input
                                                    v-model="form.gender"
                                                    type="radio"
                                                    name="gender"
                                                    value="M"
                                                    class="h-4 w-4 border-gray-300 focus:ring-2 focus:ring-blue-300 cursor-pointer"
                                                    aria-labelledby="country-option-1"
                                                    aria-describedby="country-option-1"
                                                />
                                                <label
                                                    for="country-option-1"
                                                    class="text-sm font-medium text-gray-900 ml-2 block cursor-pointer"
                                                >
                                                    Male
                                                </label>
                                            </div>

                                            <div class="flex items-center mb-4">
                                                <input
                                                    v-model="form.gender"
                                                    type="radio"
                                                    name="gender"
                                                    value="F"
                                                    class="h-4 w-4 border-gray-300 focus:ring-2 focus:ring-blue-300 cursor-pointer"
                                                    aria-labelledby="country-option-2"
                                                    aria-describedby="country-option-2"
                                                />
                                                <label
                                                    for="country-option-2"
                                                    class="text-sm font-medium text-gray-900 ml-2 block cursor-pointer"
                                                >
                                                    Female
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="w-1/2">
                                        <InputLabel
                                            for="remarks"
                                            value="Remarks"
                                        />

                                        <TextInput
                                            id="remarks"
                                            v-model="form.remarks"
                                            type="text"
                                            class="mt-1 block w-full uppercase"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center mt-4 justify-end">
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    class="w-32 justify-center"
                                    :disabled="form.processing"
                                >
                                    Update
                                </PrimaryButton>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from "@headlessui/vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
} from "@headlessui/vue";
import {
    CheckIcon,
    ChevronUpDownIcon,
    XCircleIcon,
} from "@heroicons/vue/20/solid";

const isOpen = ref(true);

function closeModal() {
    isOpen.value = false;
}
function openModal() {
    isOpen.value = true;
}

const position = [
    { id: 1, name: "Coordinator" },
    { id: 2, name: "Leader" },
    { id: 3, name: "Subleader" },
    { id: 4, name: "Member" },
];

const form = useForm({
    parent_id: "",
    name: "",
    firstname: "",
    lastname: "",
    middlename: "",
    extension: "",
    barangay: "",
    birthdate: "",
    purok: "",
    contact_no: "",
    precinct_no: "",
    gender: "",
    position: "",
    remarks: "",
});

let selected = ref("");
let query = ref("");

let filteredPosition = computed(() =>
    query.value === ""
        ? position
        : position.filter((position) =>
              position.name
                  .toLowerCase()
                  .replace(/\s+/g, "")
                  .includes(query.value.toLowerCase().replace(/\s+/g, ""))
          )
);
</script>
