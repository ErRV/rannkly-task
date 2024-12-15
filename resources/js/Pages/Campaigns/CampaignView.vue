<template>

    <Head title="Create Campaign" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Campaign Progress
                </h2>
                <button class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600"
                    @click="router.visit('/campaign-list')"> Back to Campaigns List </button>
            </div>
        </template>
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 my-4">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-5">
                <table class="min-w-full border-collapse border border-gray-200">
                    <tbody>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                Campaign Name:
                            </th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                {{campaign.name}}
                            </th>
                        </tr>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                Campaign Status:
                            </th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                {{ campaign.status }}
                            </th>
                        </tr>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                Total Contacts:
                            </th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                {{ campaign.status == 'completed' ? campaign.contacts.length : total_count }}
                            </th>
                        </tr>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                Total Processed:
                            </th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">
                                {{ campaign.status == 'completed' ? campaign.contacts.length : processed_count }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/core";
import axios from "axios";
import { reactive, ref, onMounted, onBeforeUnmount } from "vue";
import { errorToast, successToast } from '../../common.js'

const props = defineProps({
    campaign: Object,
})

const total_count = ref(0);
const processed_count = ref(0);
const interval = ref(null);

const getCampaignProcessingCount = async () => {
    try {
        const response = await axios.get(`/get-campaign-processing-count/${props.campaign.id}`);
        let data = response?.data;
        if (data.status == 200) {
            processed_count.value = data?.data?.processed || 0
            total_count.value = data?.data?.total || 0
            props.campaign.status = data?.data?.status
            if (data?.data?.status == 'completed') {
                clearInterval(interval.value);
                props.campaign.contacts = Array(data?.data?.total)
            }
        }

    } catch (error) {
        errorToast(error?.response?.data?.message);
        console.log("Failed to create campaign: ", error);
    }
};

onMounted(() => {
    if (props.campaign.status !== 'completed') {
        interval.value = setInterval(() => {
            getCampaignProcessingCount()
        }, 3000);
    }
});

onBeforeUnmount(() => {
    clearInterval(interval.value);
})

</script>