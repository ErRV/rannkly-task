<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from "@inertiajs/core";
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

defineProps({
    campaigns: {
        type: Object, 
        required: true,
    }
})

const viewCampaignContacts = ref(false);
const campaignContacts = ref({});

const viewContacts = (contacts) => {
    campaignContacts.value = contacts
    viewCampaignContacts.value = true;
};

const closeModal = () => {
    viewCampaignContacts.value = false;
    campaignContacts.value = {}
};

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    All Campaigns
                </h2>
                <button class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600" @click="router.visit('/campaign-form')"> Add Campaign </button>
            </div>
        </template>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-5">
                    <table class="min-w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-200">
                                <th
                                    class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                    Campaign Name
                                </th>
                                <th
                                    class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                    Status
                                </th>
                                <th
                                    class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                    View Contacts
                                </th>
                                <th
                                    class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                    View Progress
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!campaigns?.length">
                                <td colspan="4" class="text-center py-10">No Campaigns Found</td>
                            </tr>
                            <tr v-else v-for="camp in campaigns" :key="camp.id" class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">{{ camp.name }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">{{ camp.status }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">
                                    <button class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600" @click="viewContacts(camp)">
                                        View
                                    </button>
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">
                                    <button class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600" @click="router.visit(`/campaign/${camp.id}`)">
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Modal :show="viewCampaignContacts" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900"
                >
                Contacts List of Campaign: {{ campaignContacts.name }}
                </h2>
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-5">
                    <table class="min-w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-200">
                                <th
                                    class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                    User Name
                                </th>
                                <th
                                    class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                    Email
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!campaignContacts?.contacts?.length">
                                <td colspan="2" class="text-center py-5">No Contacts Found</td>
                            </tr>
                            <tr v-else v-for="user in campaignContacts.contacts" :key="user.id" class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">{{ user.name }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm text-gray-700">{{ user.email }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
