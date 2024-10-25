<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ListTick from "@/Components/ListTick.vue";
import Arrow from "@/Components/Arrow.vue";

const goTo = (url) => {
    window.location.href = url
}
</script>

<template>
    <section class="md:p-12 p-6 mx-auto">
        <div class="px-8 pb-6 flex flex-col gap-10 lg:gap-14 items-center text-center">
            <h1 class="md:text-7xl text-3xl my-6 font-extrabold leading-normal">
                Turn your
                <span class="border-b-8 border-dashed border-primary/50 italic">ideas</span>
                <br>
                into
                <span class="border-b-8 border-dashed border-primary/50 italic">successful projects</span>
            </h1>
            <div class="text-lg max-w-xl mx-auto">
                Our AI-powered tool not only helps you track progress and collaborate, but also provides valuable
                insights and information on your project's potential, including competitor analysis.
                <br>
                Save time and make informed decisions with Progr.io.
            </div>
            <ul class="space-y-1.5">
                <li class="flex items-center justify-start gap-2">
                    <ListTick>Track your projects</ListTick>
                </li>
                <li class="flex items-center justify-start gap-2">
                    <ListTick>Get help from AI</ListTick>
                </li>
            </ul>
            <PrimaryButton @click="goTo(route('dashboard'))">
                <Arrow>Get progr.io</Arrow>
            </PrimaryButton>
            <div style="position: relative; padding-bottom: calc(48.645833333333336% + 41px); height: 0; width: 100%;">
                <iframe src="https://demo.arcade.software/OVSUrx4v4hU8hUaygetH?embed&show_copy_link=true"
                        title="Projects - Progr.io" frameborder="0" loading="lazy" webkitallowfullscreen
                        mozallowfullscreen allowfullscreen allow="clipboard-write"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;color-scheme: light;"></iframe>
            </div>

            <h3 class="font-extrabold md:text-2xl text-xl">
                You can also use the idea generator if you are out of ideas.
            </h3>
            <div class="aspect-video w-full sm:w-1/2">
                <iframe class="w-full h-full" src="https://www.youtube.com/embed/r-fareAicFk?si=RA3DayV6TycK7ICT"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>

        </div>
    </section>
</template>
