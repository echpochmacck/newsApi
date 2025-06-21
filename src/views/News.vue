<template>
    <div v-if="isLoading" class="container d-flex justify-content-center mt-5 align-items-center">
        <Loader />
    </div>
    <div class="container mt-5" v-else>
        <!-- Search Form -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                <form class="w-50 d-flex gap-3 align-items-center">
                    <input class="form-control" type="search" placeholder="Search" aria-label="Search" v-model="query">
                    <button class="btn btn-outline-primary" type="submit" @click.prevent="fetchNews()">Search</button>
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <h2 class="text-center mb-4">News</h2>
            </div>
        </div>
        <!-- Card Grid -->
        <div class="row g-4" v-if="dataFromApi.articles">
            <div class="col-md-4" v-for="item, index in dataFromApi.articles" :key="index">
                <NewsItem :item="item" />
            </div>
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center" v-if="dataFromApi.totalResults > 1">
                <nav aria-label="Page navigation" v-if="dataFromApi.articles && dataFromApi.articles">
                    <ul class="pagination">
                        <li class="page-item" :class="page==1 && 'disabled'">
                            <button class="page-link" @click.prevent="fetchNews(--page)">Previous</button>
                        </li>

                        <!-- исправить -->
                        <!-- <li class="page-item" v-for="(i, index) in maxPage" v-if="i <= dataFromApi.totalResults">
                            <button class="page-link" @click.prevent="fetchNews(i)"
                                :class="page==i?'active':''">{{i}}</button>
                        </li> -->
                        <li class="page-item">
                            <button class="page-link" @click.prevent="fetchNews(++page)">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</template>
<script setup>
    import {ref, onMounted, inject} from 'vue'
    import Loader from '@/components/Loader.vue'
    import NewsItem from '@/components/NewsItem.vue'
    onMounted(() => {
        fetchNews()
    })
    const url = inject('url')
    const page = ref(1);
    const isLoading = ref(true);
    const dataFromApi = ref({});
    const maxPage = ref(3)
    const query = ref('')
    async function fetchNews(curr) {
        try {
            if (curr) {
                page.value = curr;
                maxPage.value = curr + 1
            }


            const myHeaders = new Headers();

            const requestOptions = {
                method: "GET",
                headers: myHeaders,
                redirect: "follow"
            };

            const result = await fetch(`${url}/news?page=${page.value > 1 ? curr : 1}${query.value ? '&query=' + query.value : ''}`, requestOptions)
            const data = await result.json()
            if (result.status > 199 && result.status < 300) {
                dataFromApi.value = data.data[0]


            }
        } catch (error) {
            console.log(error)
        } finally {
            isLoading.value = false;
        }
    }
</script>
<style scoped>
    .container {
        height: 70vh;
    }
</style>