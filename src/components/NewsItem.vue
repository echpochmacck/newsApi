<template>
    <div class="card h-100">
        <img :src="item.urlToImage" class="card-img-top" alt="Nintendo Switch 2">
        <div class="card-body">
            <h5 class="card-title">{{item.title}}</h5>
            <p class="card-text">
                {{item.description}}
            </p>
            <p class="text-muted mb-0">By {{item.author}}</p>
            <p class="text-muted">{{item.publishedAt}}</p>
        </div>
        <div class="card-footer">
            <a :href="item.url" target="_blank" class="btn btn-primary w-100">Read More</a>
        </div>
    </div>
</template>
<script setup>
    import {defineProps, defineEmits} from 'vue'
    const props = defineProps({
        item: Object
    })
</script>