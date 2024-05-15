<script setup>
import {onMounted} from "vue";

onMounted(() => {
    const adElement = document.getElementById('ad-element');

    const script1 = document.createElement('script');
    script1.async = true;
    script1.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-0882802698917065';
    script1.setAttribute('crossorigin', 'anonymous');
    adElement.appendChild(script1);

    const ins = document.createElement('ins');
    ins.style.display = 'block';
    ins.setAttribute('class', 'adsbygoogle');
    ins.setAttribute('data-ad-client', 'ca-pub-0882802698917065');
    ins.setAttribute('data-ad-slot', '3474737115');
    ins.setAttribute('data-ad-format', 'auto');
    ins.setAttribute('data-full-width-responsive', 'true');
    adElement.appendChild(ins);

    const script2 = document.createElement('script');
    script2.innerHTML = '(adsbygoogle = window.adsbygoogle || []).push({});';
    adElement.appendChild(script2);
})
</script>
<template>
    <div id="ad-element"><!-- progr.io --></div>
</template>
