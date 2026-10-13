<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

interface Slide {
  title: string;
  background_image: string;
}

interface Props {
  block: {
    type: string;
    data: {
      slides: Slide[];
    };
  };
}

const props = defineProps<Props>();

const getImageUrl = (path: string) => {
  return `/storage/${path}`;
};
</script>

<template>

  <section v-if="props.block.data.slides && props.block.data.slides.length > 0" class="hero-block relative">
    <!-- Multiple slides with content and images -->
    <div class="swiper swiper--hero slider-item" data-slider-index="0">
      <div class="swiper-wrapper">
        <div v-for="(slide, index) in props.block.data.slides" class="swiper-slide relative">
          <!-- Background Image -->
          <div class="absolute inset-0 z-0">
            <img v-if="slide.background_image" :src="getImageUrl(slide.background_image)"
              :alt="slide.title ?? 'Hero Image ' + (index + 1)" class="w-full h-full object-cover">
            <div v-else class="w-full h-full"></div>
          </div>

          <!-- Content -->
          <div class="relative z-10 min-h-screen flex">
            <div v-if="slide.title" class="container mx-auto animate hidden lg:block">
              <div v-html="slide.title" class="font-bold slide-title relative animate-top"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation buttons -->
      <div v-if="props.block.data.slides.length > 0"
        class="navigation absolute top-1/2 transform -translate-y-1/2 w-full flex justify-between px-4 z-20">
        <button
          class="prev cursor-pointer bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-3 transition-colors duration-300">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        <button
          class="next cursor-pointer bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-3 transition-colors duration-300">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>

      <!-- Dots indicator -->
      <div class="swiper-pagination"></div>
    </div>
  </section>
</template>
