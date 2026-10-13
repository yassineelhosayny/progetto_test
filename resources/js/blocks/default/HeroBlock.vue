<script setup lang="ts">
import { onMounted, ref, onBeforeUnmount } from 'vue';
import Player from '@vimeo/player';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { ChevronDownIcon } from '@heroicons/vue/16/solid';
gsap.registerPlugin(ScrollTrigger);
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
gsap.registerPlugin(ScrollToPlugin);


interface Props {
    block: {
        type: string;
        data: {
            title: string;
            background_image: string | null;
            background_video: string | null;
            embed_url: string | null;
            type: string;
        };
    };
}

const iframeContainer = ref<HTMLDivElement | null>(null);
const videoContainer = ref<HTMLVideoElement | null>(null);
const imageContainer = ref<HTMLImageElement | null>(null);
const iframeElement = ref<HTMLIFrameElement | null>(null);
const showVimeoPoster = ref(true);

let vimeoPlayer: Player | null = null;

const hideVimeoPoster = () => {
    showVimeoPoster.value = false;
};

const props = defineProps<Props>();

const getMediaUrl = (path: string) => {
    return `/storage/${path}`; ;
};

const scrollDown = () => {
    let offset = window.innerHeight - 80;
    if(props.block.data.type === 'embed_video') {
        if(!iframeContainer.value) return;
        offset = iframeContainer.value?.clientHeight > window.innerHeight ? window.innerHeight : iframeContainer.value?.clientHeight || offset;
    } else if(props.block.data.type === 'local_video') {
        if(!videoContainer.value) return;
        offset = videoContainer.value?.clientHeight > window.innerHeight ? window.innerHeight : videoContainer.value?.clientHeight || offset;
    } else {
        if(!imageContainer.value) return;
        offset = imageContainer.value?.clientHeight > window.innerHeight ? window.innerHeight : imageContainer.value?.clientHeight || offset;
    }
    console.log('Offset:', offset);
    gsap.to(window, {
        scrollTo: { y : offset - 80 },
        duration: 0.6,
        ease: 'power3.out',
    });
};

const getData = () => {
    switch (props.block.data.type) {
        case 'image':
            return {
                background: props.block.data.background_image
                    ? getMediaUrl(props.block.data.background_image)
                    : undefined,
                isVideo: false,
                isEmbed: false,
            };
        case 'local_video':
            return {
                background: props.block.data.background_video
                    ? getMediaUrl(props.block.data.background_video)
                    : undefined,
                isVideo: true,
                isEmbed: false,
            };
        case 'embed_video':
            return {
                background: props.block.data.embed_url || undefined,
                isVideo: true,
                isEmbed: true,
            };
        default:
            return { background: undefined, isVideo: false, isEmbed: false };
    }
}

onMounted(() => {
    if (props.block.data.type === 'embed_video' && iframeElement.value) {
        vimeoPlayer = new Player(iframeElement.value);
        vimeoPlayer.on('play', hideVimeoPoster);

        vimeoPlayer.on('timeupdate', (data: { seconds: number }) => {
            if (data.seconds > 0.05) {
                hideVimeoPoster();
            }
        });
    }
});

onBeforeUnmount(() => {
    if (vimeoPlayer) {
        vimeoPlayer.unload();
        vimeoPlayer = null;
    }
});

</script>

<template>
    <section class="hero-block">
        <div class="w-full overflow-hidden -z-10">
            <div class="hero-block-container relative">
                <div ref="mediaContainer" class="absolute inset-0 z-0 w-full h-full">
                    <img ref="imageContainer" v-if="!getData().isVideo" :src="getData().background"
                        :alt="props.block.data.title ?? 'Hero Image'" class="w-full h-full object-cover">
                    <div v-else-if="getData().isEmbed && getData().isVideo" class="max-h-screen overflow-hidden">
                        <div ref="iframeContainer" class="relative overflow-hidden max-w-[full] h-[calc(56.25vw+10px)]">
                            
                            <div v-if="showVimeoPoster"
                                class="absolute inset-0 z-10 transition-all duration-500 flex justify-center items-center animation-ping"
                                :class="{ 'opacity-0 pointer-events-none': !showVimeoPoster }">
                                
                                <img src="/images/logo-loader.gif"
                                    :alt="props.block.data.title ?? 'Video poster'"
                                    class="w-1/3 h-auto object-cover">
                            </div>
                            
                            <iframe ref="iframeElement" :src="getData().background" frameborder="0" class="h-full w-full border-0 transition-opacity duration-500"
                                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen :class="{ 'opacity-100': !showVimeoPoster, 'opacity-0': showVimeoPoster }"></iframe>
                        </div>
                    </div>
                    <div v-else>
                        <video ref="videoContainer" v-if="getData().background" :src="getData().background" autoplay muted loop
                            class="w-full h-full object-cover"></video>
                        <div v-else class="w-full h-full"></div>
                    </div>
                </div>
                <ChevronDownIcon class="absolute hidden bottom-4 z-20 left-1/2 transform -translate-x-1/2 text-white w-12 h-12 animate-bounce cursor-pointer lg:block" @click="scrollDown" />

                <!-- Content -->
                <div class="relative z-10 max-w-screen max-h-screen aspect-video">
                    <div v-if="props.block.data.title" class="container mx-auto flex items-center justify-center animate h-full">
                        <div v-html="props.block.data.title" class="font-bold slide-title relative animate-top"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>