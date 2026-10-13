<script setup lang="ts">
import { locale } from '@/utils/localize';
import type { NewsItem } from '../composables/useNews'
import { BackspaceIcon } from '@heroicons/vue/16/solid';
import { CalendarDaysIcon } from '@heroicons/vue/24/outline';

const props = defineProps<{
    news: NewsItem,
    featuredNews?: NewsItem[],
}>()

const lang: string | any = locale().indexOf('-') ? locale().split('-')[0] : locale();

function getUrlImage(path: string | null): string {
    return `/storage/${path}`;
}

function formatDate(dateString: string): string {
    const date = new Date(dateString);
    return date.toLocaleDateString(lang, { year: 'numeric', month: 'long', day: 'numeric' });
}

</script>

<template>
    <section class="content">
    <article class="single-news bg-gray-50 min-h-screen">
        <div class="container grid grid-cols-[70%_30%] gap-8 py-12">
            <!-- News Content -->
            <section>
                <div class="image w-[85%]">
                    <img v-if="props.news.excerpt_image" :src="getUrlImage(props.news.excerpt_image)" :alt="props.news.title[lang]"
                        class="w-full h-auto object-cover mb-6 rounded-lg shadow-md">
                </div>
                <!-- Title -->
                <h3 class="text-4xl font-bold text-gray-500">{{ props.news.title[lang] }}</h3>
                <ul class="list-disc">
                    <li class="flex items-center gap-2 text-gray-500 my-4">
                        <CalendarDaysIcon class="w-6 h-6"></CalendarDaysIcon>
                        <time :datetime="props.news.published_at">
                            {{ formatDate(props.news.published_at) }}
                        </time>
                    </li>
                </ul>

                <!-- Excerpt -->
                    <div class="border-b border-gray-300 pb-8 mb-8">
                        <h6 class="text-gray-500" v-html="props.news.excerpt[lang]"></h6>
                    </div>

                <!-- Content -->
                <div v-if="props.news.description[lang]" class="description flex flex-col gap-8 text-gray-500 mt-6" v-html="props.news.description[lang]"></div>
            </section>

            <!-- Back to news -->
            <aside>
                <a href="/news"
                    class="bg-[#00466A] px-4 py-1 uppercase text-white inline-flex items-center gap-4 rounded-sm">
                    <div class="xsmall"><b>Back to list</b></div>
                    <BackspaceIcon class="h-4"></BackspaceIcon>
                </a>
            </aside>
        </div>
    </article>
</section>
</template>