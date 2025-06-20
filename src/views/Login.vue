<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Login</h2>
                <form id="loginForm" novalidate>
                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                            :class="errors?.email ? 'is-invalid' : ''" v-model="email" required>
                        <div id="emailError" class="text-danger">
                            {{errors?.email}}</div>
                    </div>

                    <!-- Password Field -->
                    <div class=" mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" v-model="password"
                            :class="errors?.password ? 'is-invalid' : ''" placeholder="Enter your password" required>
                        <div id="passwordError" class="text-danger">
                            {{errors?.password}}</div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100" @click.prevent="fetchLogin">Login</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
    import {ref, inject} from 'vue'
    import {useRouter} from 'vue-router'
    const url = inject('url')
    const router = useRouter()
    const token = inject('token')
    const avatar = inject('avatar')

    const password = ref('')
    const email = ref('')
    const errors = ref({});

    async function fetchLogin() {
        try {
            clear()
            const myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

            const raw = JSON.stringify({
                "email": email.value,
                "password": password.value
            });

            const requestOptions = {
                method: "POST",
                headers: myHeaders,
                body: raw,
                redirect: "follow"
            };
            const result = await fetch(`${url}/login`, requestOptions);
            const data = await result.json()
            if (result.status > 199 && result.status < 300) {
                console.log(token)
                token.value = data.data.token;
                avatar.value = data.data.avatar_url;
                localStorage.setItem('avatar', data.data.avatar_url)
                localStorage.setItem('token', data.data.token)
                router.push({name:'news'})
            }
            if (result.status == 422) {
                downloadError(data.error.errors)
            }
            if (result.status == 401) {
                errors.value.password = 'login failed'
            }

        } catch (e) {
            console.log(e)
        }
    }

    function downloadError(arr) {
        for (let attr in arr) {
            errors.value[attr] = arr[attr][0]
        }
        console.log(errors.value)
    }
    function clear() {
        errors.value = {};
    }

</script>