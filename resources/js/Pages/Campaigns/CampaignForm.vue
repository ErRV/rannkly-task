<template>

    <Head title="Create Campaign" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    All Campaigns
                </h2>
                <button class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600" @click="router.visit('campaign-list')"> Back </button>
            </div>
        </template>

        <div class="mx-auto max-w-lg sm:px-6 lg:px-8 my-5">
            <form @submit.prevent="submitCampaign">
                <div class="my-2">
                    <InputLabel for="campaignName" value="Campaign Name" />
                    <TextInput id="campaignName" type="text" class="mt-1 block w-full" v-model="form.campaignName"
                        required autofocus />
                    <InputError v-for="(item, index) in errorList?.campaign_name" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="my-2">
                    <InputLabel for="campaignFile" value="Campaign CSV File" />
                    <TextInput id="campaignFile" type="file" class="mt-1 block w-full" required
                        @change="handleFileUpload" />
                    <InputError v-for="(item, index) in errorList?.csv_file" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="my-2">
                    <PrimaryButton class="my-4" :class="{ 'opacity-25': loading }" :disabled="loading">
                        Submit
                        <div v-if="loading" class="w-5 h-5 border-4 border-white-500 border-t-transparent rounded-full animate-spin ms-2"></div>
                     </PrimaryButton>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/core";
import axios from "axios";
import { reactive, ref } from "vue";
import { errorToast, successToast } from '../../common.js'

const form = reactive({
    campaignName: "",
    csvFile: null,
});

const loading = ref(false);
const errorList = ref({});

const handleFileUpload = (event) => {
    form.csvFile = event.target.files[0];
};

const submitCampaign = async () => {
    loading.value = true
    const formData = new FormData();
    formData.append("campaign_name", form.campaignName);
    formData.append("csv_file", form.csvFile);
    try {
        const response = await axios.post("/campaign-form", formData);
        let data = response?.data;        
        if(data.status == 200) {
            let camp_id = data?.data?.id
            successToast(data?.message);
            loading.value = false
            router.visit(`/campaign/${camp_id}`, {replace: true});
        }
        
    } catch (error) {
        loading.value = false
        form.csvFile = null
        document.getElementById('campaignFile').value = "";
        errorToast(error?.response?.data?.message);
        errorList.value = error?.response?.data?.errors
        console.log("Failed to create campaign: ",  errorList.value);
    }
};

</script>