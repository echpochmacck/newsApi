<template>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Register</h2>
                <form id="registerForm" novalidate>
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
                            :class="errors?.name ? 'is-invalid' : ''" v-model="name" required>
                        <div id="nameError" class="text-danger">
                            {{errors?.name}}
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                            :class="errors?.email ? 'is-invalid' : ''" v-model="email" required>
                        <div id="emailError" class="text-danger">
                            {{errors?.email}}
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            :class="errors?.password ? 'is-invalid' : ''" placeholder="Enter your password"
                            v-model="password" required>
                        <div id="passwordError" class="text-danger">
                            {{errors?.password}}
                        </div>
                    </div>

                    <!-- Avatar Upload Field -->
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Upload Avatar</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" ref="avatar">
                        <div id="avatarError" class="text-danger">
                            {{errors?.avatar}}
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100" @click.prevent="fetchRegister">Register</button>
                </form>
            </div>
        </div>
    </div>

</template>
<script setup>
    import {ref, inject, useTemplateRef} from 'vue'
    import {useRouter} from 'vue-router'
    const url = inject('url')
    const router = useRouter()

    const password = ref('123Da')
    const email = ref('test@create.com')
    const name = ref('max')
    const errors = ref({})
    const input = useTemplateRef('avatar')

    async function fetchRegister() {
        try {
            clear()

            const formdata = new FormData();
            formdata.append("email", email.value);
            formdata.append("password", password.value);
            formdata.append("avatar", avatar.files[0]);
            formdata.append("name", name.value);

            const requestOptions = {
                method: "POST",
                body: formdata,
                redirect: "follow"
            };

            const result = await fetch(`${url}/register`, requestOptions);
            const data = await result.json()
            if (result.status > 199 && result.status < 300) {
                // поменять на что-то более симпотное
                alert('Регистрация успешна')
                router.push({name: 'home'});
            }
            if (result.status == 422) {
                downloadError(data.error.errors)
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