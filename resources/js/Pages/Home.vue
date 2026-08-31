<script setup>
defineProps({
    appName: String,
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                🥥 {{ appName }}
            </h1>
            <p class="text-gray-600 text-lg mb-8">
                Laravel + Inertia.js + Vue 3 is running!
            </p>
            <div class="flex gap-4 justify-center text-sm">
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">✅ Laravel</span>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">✅ Inertia.js</span>
                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full font-medium">✅ Vue 3</span>
                <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full font-medium">✅ MySQL</span>
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-medium">✅ Docker</span>
            </div>
        </div>
    </div>
</template>
