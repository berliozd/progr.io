<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ErrorAlert from "@/Components/ErrorAlert.vue";
import Box from "@/Components/Box.vue";

import {Head, useForm, usePage} from '@inertiajs/vue3';
import {computed, onMounted} from "vue"

const error = computed(() => usePage().props.error)
const props = defineProps({
    stripeKey: String,
    products: Array,
    intent: Object,
    errors: Object
});
const form = useForm({
    plan: '',
    cardHolderName: '',
    promoCode: ''
});

onMounted(() => {
    let documentTag = document;
    let tag = 'script';
    let object = documentTag.createElement(tag);
    let scriptTag = documentTag.getElementsByTagName(tag)[0];
    object.src = '//' + 'js.stripe.com/v3/';
    object.addEventListener('load', function (e) {
        window.stripe = Stripe(props.stripeKey);
        const elements = window.stripe.elements();
        window.cardElement = elements.create('card');
        window.cardElement.mount('#card-element');
    }, false);
    scriptTag.parentNode.insertBefore(object, scriptTag);
});

const submit = () => {
    usePage().props.error = '';
    window.stripe.createPaymentMethod(
        'card',
        window.cardElement,
        {billing_details: {name: form.cardHolderName}}
    ).then(
        function (result) {
            if (result.error) {
                usePage().props.error = result.error.message;
            } else {
                form.transform((data) => ({
                    ...data,
                    paymentMethod: result.paymentMethod.id,
                })).post(route('subscribe.store'));
            }
        }
    );
};


</script>
<template>
    <Head v-bind:title="$t('Create subscription')"/>

    <AuthenticatedLayout>
        <template #header>
            <PageHeader v-bind:title="$t('Create subscription')"/>
        </template>

        <form @submit.prevent="submit" class="space-y-5">

            <ErrorAlert v-if="error" v-bind:error="error"/>

            <Box>
                <fieldset>
                    <legend class="text-gray-800 dark:text-gray-400">{{ $t('Plan : ') }}</legend>
                    <div class="">
                        <div v-for="product in products" class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input v-bind:id="product.id"
                                       v-bind:value="product.id"
                                       aria-describedby="small-description"
                                       name="plan"
                                       type="radio"
                                       v-model="form.plan"
                                       checked
                                       class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                >
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label v-bind:for=" product.id "
                                       class="font-medium text-gray-800 dark:text-gray-400">{{ product.name }}</label>
                                <p id="small-description" class="text-gray-500">{{ product.price }} €</p>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </Box>

            <Box>
                <input-label for="card-holder-name" value="Card owner :"/>
                <text-input id="card-holder-name" type="text" v-model="form.cardHolderName"/>
            </Box>

            <Box>
                <input-label for="promo-code" value="Promo code :"/>
                <text-input id="promo-code" type="text" v-model="form.promoCode"/>
            </Box>

            <Box>
                <input-label for="card-element" value="Card :"/>
                <div id="card-element" class="shadow-lg p-5 rounded bg-white"></div>
            </Box>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ $t('Subscribe') }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
