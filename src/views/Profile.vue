<template>


    <div class="container mt-5 d-flex justify-content-center align-items-center">
        <Loader v-if="isLoading" />
        <div class="d-flex justify-content-center align-items-center flex-column gap-3" v-else>

            <div>
                <h1>Профиль пользователя</h1>
            </div>
            <div class="border rounded border-primary user_block" v-if="user">
                <div class="d-flex justify-content-between w-100 align-items-center p-3">
                    <div class="d-flex flex-column gap-3">
                        <div>Почта:<span class="text-primary">{{user.email}}</span></div>
                        <div>Имя:<span class="text-primary">{{user.name}}</span></div>
                    </div>
                    <div class="user_avatar" :style="{
                        backgroundImage:`url('${avatar_url}')`}"></div>
                </div>
            </div>
        </div>

    </div>
</template>
<script setup>
    import {ref, provide, inject, onMounted} from 'vue'
    import Loader from '@/components/Loader.vue'
    const user = ref('');
    const token = inject('token')
    const url = inject('url');
    const avatar_url = ref('');
    const isLoading = ref(true);
    async function fetchUserInfo() {
        try {

            const myHeaders = new Headers();
            myHeaders.append("Authorization", "Bearer " + token.value);


            const requestOptions = {
                method: "GET",
                headers: myHeaders,
            };

            const result = await fetch(`${url}/user/info`, requestOptions)
            const data = await result.json()
            if (result.status > 199 && result.status < 300) {
                user.value = data.data.user
                avatar_url.value = data.data.avatar
            }
        } catch (e) {
            console.log(e)
        } finally {
            isLoading.value = false
        }
    }

    onMounted(() => {
        fetchUserInfo();
    })

</script>
<style scoped>
    .container {
        height: 70vh;
    }
    .user_block {
        width: 70vw;
    }

    .user_avatar {
        border-radius: 50%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        width: 150px;
        height: 150px;
        filter: drop-shadow(0px 0px 10px blue);
        animation: .4s shadowAnim alternate-reverse infinite;
    }

    @keyframes shadowAnim {
        0% {
            filter: drop-shadow(0px 0px 10px blue);

        }

        100% {
            filter: drop-shadow(0px 0px 15px blue);

        }

    }
</style>
