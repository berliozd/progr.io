<script setup></script>
<template>
  <footer class="border-t-2 border-base-content/10">
    <div class="max-w-7xl mx-auto px-8 py-24">
      <div class=" flex lg:items-start md:flex-row md:flex-nowrap flex-wrap flex-col">
        <div class="w-64 flex-shrink-0 md:mx-0 mx-auto text-center md:text-left">
          <a aria-current="page" class="flex gap-2 justify-center md:justify-start items-center" href="/#">
            <strong class="font-extrabold tracking-tight text-base md:text-lg">Progr.io</strong>
          </a>
          <p class="mt-3 text-sm">Online ideas tracking tool for indie hackers, teams, freelancers, and
            entrepreneurs.</p>
          <p class="mt-3 text-sm text-base-content/60">Copyright © 2024 - All rights reserved</p>
        </div>
        <div class="flex-grow flex flex-wrap justify-center -mb-10 md:mt-0 mt-10 text-center md:pl-24">
          <div class="lg:w-1/3 md:w-1/2 w-full px-4">
            <div class="footer-title font-semibold text-base-content tracking-widest text-sm md:text-left mb-3">LINKS
            </div>
            <div class="flex flex-col justify-center items-center md:items-start gap-2 mb-10 text-sm">
              <a class="link link-hover" href="/dashboard">Login</a>
              <a class="link link-hover" href="#pricing" v-smooth-scroll>Pricing</a>
            </div>
          </div>
          <div class="lg:w-1/3 md:w-1/2 w-full px-4">
            <div class="footer-title font-semibold text-base-content tracking-widest text-sm md:text-left mb-3">LEGAL
            </div>
            <div class="flex flex-col justify-center items-center md:items-start gap-2 mb-10 text-sm">
              <a class="link link-hover" href="/terms">Terms and conditions</a>
              <a class="link link-hover" href="/privacy-policy">Privacy policy</a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </footer>
</template>
