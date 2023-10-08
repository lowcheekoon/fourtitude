<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import List from './List.vue';
import Form from './Form.vue';


const props = defineProps({
  products: Object
});

const page = ref('list');
const action = ref('new');
const code = ref('');

function receiveGoList() {
    page.value = 'list';
}

function receiveGoForm(actionValue, codeValue) {
    action.value = actionValue;
    code.value = codeValue;
    page.value = 'from';
}

</script>

<template>
    <Head title="Product" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Product</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div v-if="page=='list'" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <List @go-form="receiveGoForm" ></List>
                </div>

                <div v-if="page=='from'" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <Form @go-back="receiveGoList" :code=code :action=action></Form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
