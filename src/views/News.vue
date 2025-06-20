<template>
    <div class="container mt-5">
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item" :class="page==1 && 'disabled'">
                            <button class="page-link" @click.prevent="fetchNews(--page)">Previous</button>
                        </li>

                        <li class="page-item">
                            <button class="page-link" @click.prevent="fetchNews(++page)">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row">
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
            <div class="col-12 d-flex justify-content-center">
                <nav aria-label="Page navigation" v-if="dataFromApi.articles">
                    <ul class="pagination">
                        <li class="page-item" :class="page==1 && 'disabled'">
                            <button class="page-link" @click.prevent="fetchNews(--page)">Previous</button>
                        </li>
                        <li class="page-item" v-for="i, index in maxPage">
                            <button class="page-link" @click.prevent="fetchNews(i)"
                                :class="page==i?'active':''">{{i}}</button>
                        </li>
                        <li class="page-item">
                            <button class="page-link" @click.prevent="fetchNews(i)">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</template>
<script setup>
    import {ref, onMounted} from 'vue'
    import NewsItem from '@/components/NewsItem.vue'
    onMounted(() => {
        fetchNews()
    })
    const page = ref(1);
    const dataFromApi = ref({});
    const maxPage = ref(3)
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

            const result = await fetch(`http://newsapi/api/news?page=${page.value > 1 ? curr : 1}`, requestOptions)
            const data = await result.json()
            if (result.status > 199 && result.status < 300) {
                dataFromApi.value = data.data[0]
            }
        } catch (error) {
            consnole.log(error)
        }
    }


</script>