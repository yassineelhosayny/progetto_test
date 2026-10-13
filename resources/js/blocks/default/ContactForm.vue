<script setup lang="ts">
interface Props {
    block: {
        type: string;
        data: {
            title?: string;
            description?: string;
            labels?: { name?: string; email?: string; phone?: string; subject?: string; message?: string };
            placeholders?: { name?: string; email?: string; message?: string };
            fields?: { phone?: boolean; subject?: boolean };
            privacy?: { enabled?: boolean; text?: string; link_text?: string; url?: string };
            submit_text?: string;
            submitting_text?: string;
            wrapper_classes?: string;
        };
    }
}

const props = defineProps<Props>();
</script>

<template>
    <section :class="props.block.data.wrapper_classes || 'bg-white p-8 rounded-lg'">
        <div class="container mx-auto px-4">
            <h3 v-if="props.block.data.title" class="text-2xl font-bold mb-2">{{ props.block.data.title }}</h3>
            <p v-if="props.block.data.description" class="text-gray-600 mb-4">{{ props.block.data.description }}</p>

            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">{{ props.block.data.labels?.name || 'Name' }}</label>
                    <input :placeholder="props.block.data.placeholders?.name || ''" class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ props.block.data.labels?.email || 'Email' }}</label>
                    <input :placeholder="props.block.data.placeholders?.email || ''" class="w-full border rounded px-3 py-2" />
                </div>

                <div v-if="props.block.data.fields?.phone">
                    <label class="block text-sm font-medium">{{ props.block.data.labels?.phone || 'Phone' }}</label>
                    <input class="w-full border rounded px-3 py-2" />
                </div>

                <div v-if="props.block.data.fields?.subject">
                    <label class="block text-sm font-medium">{{ props.block.data.labels?.subject || 'Subject' }}</label>
                    <input class="w-full border rounded px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium">{{ props.block.data.labels?.message || 'Message' }}</label>
                    <textarea :placeholder="props.block.data.placeholders?.message || ''" class="w-full border rounded px-3 py-2" rows="4"></textarea>
                </div>

                <div v-if="props.block.data.privacy?.enabled" class="text-sm">
                    <label>
                        <input type="checkbox" /> {{ props.block.data.privacy?.text || 'I accept the' }} <a :href="props.block.data.privacy?.url">{{ props.block.data.privacy?.link_text }}</a>
                    </label>
                </div>

                <div>
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">{{ props.block.data.submit_text || 'Send' }}</button>
                </div>
            </form>
        </div>
    </section>
</template>
