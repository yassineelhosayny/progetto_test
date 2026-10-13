<script setup lang="ts">
import { useContactForm } from '../../composables/useContactForm'

type Block = { type: string; data?: Record<string, any> }

const props = defineProps<{
    block: Block
    action?: string
}>()

const data = props.block?.data || {}

const {
    title,
    description,
    labels,
    placeholders,
    fields,
    privacy,
    submitText,
    submittingText,
    wrapperClass,
    form,
    errors,
    submitting,
    status,
    statusClass,
    submit,
} = useContactForm(data, props.action || '/contact')
</script>
<template>
    <section :class="wrapperClass">
        <div class="max-w-3xl mx-auto">
            <div v-if="title || description" class="mb-6">
                <h2 v-if="title" class="text-2xl font-bold mb-2" v-html="title"></h2>
                <p v-if="description" class="text-gray-600" v-html="description"></p>
            </div>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ labels.name }}</label>
                        <input v-model="form.first_name" :placeholder="placeholders.name" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        <p v-if="errors.first_name" class="text-red-600 text-sm mt-1">{{ errors.first_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ labels.last_name }}</label>
                        <input v-model="form.last_name" :placeholder="placeholders.name" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        <p v-if="errors.last_name" class="text-red-600 text-sm mt-1">{{ errors.last_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ labels.email }}</label>
                        <input v-model="form.email" :placeholder="placeholders.email" type="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        <p v-if="errors.email" class="text-red-600 text-sm mt-1">{{ errors.email }}</p>
                    </div>

                    <div v-if="fields.phone">
                        <label class="block text-sm font-medium text-gray-700">{{ labels.phone }}</label>
                        <input v-model="form.phone" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        <p v-if="errors.phone" class="text-red-600 text-sm mt-1">{{ errors.phone }}</p>
                    </div>

                    <div v-if="fields.subject" class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">{{ labels.subject }}</label>
                        <input v-model="form.subject" type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        <p v-if="errors.subject" class="text-red-600 text-sm mt-1">{{ errors.subject }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">{{ labels.message }}</label>
                    <textarea v-model="form.message" :placeholder="placeholders.message" rows="6"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    <p v-if="errors.message" class="text-red-600 text-sm mt-1">{{ errors.message }}</p>
                </div>

                <div v-if="privacy.enabled" class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" v-model="form.privacy" class="form-checkbox" />
                        <span class="ml-2 text-sm text-gray-700">{{ privacy.text }} <a :href="privacy.url"
                                class="underline">{{ privacy.link_text }}</a></span>
                    </label>
                    <p v-if="errors.privacy" class="text-red-600 text-sm mt-1">{{ errors.privacy }}</p>
                </div>

                <div class="mt-6 flex items-center justify-between">
                    <button :disabled="submitting" type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                        <span v-if="!submitting">{{ submitText }}</span>
                        <span v-else>{{ submittingText }}</span>
                    </button>

                    <p v-if="status" :class="statusClass">{{ status }}</p>
                </div>
            </form>
        </div>
    </section>
</template>
