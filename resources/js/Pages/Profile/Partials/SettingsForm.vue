<script setup>
import {useForm, usePage} from '@inertiajs/vue3';
import {ref} from 'vue';
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const receiveWeeklyEmailInput = ref(null);
const user = usePage().props.auth.user;
const form = useForm({
    receive_weekly_email: JSON.parse(user.settings).receive_weekly_email ?? false,
});

const updateSettings = () => {
    form.patch(route('settings.update'), {
        preserveScroll: true
    });
};
</script>
<template>
    <section>
        <header>
            <h2 class="text-lg font-medium">Settings</h2>
        </header>
        <form @submit.prevent="updateSettings" class="mt-6 space-y-6">
            <div class="flex space-x-2">
                <Checkbox :checked="form.receive_weekly_email" id="receive_weekly_email" name="receive_weekly_email"
                          ref="receiveWeeklyEmailInput" v-model="form.receive_weekly_email"/>
                <label for="receive_weekly_email" class="block text-sm font-medium">
                    Receive weekly email with 5 ideas
                </label>
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
