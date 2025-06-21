<template>
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">404</h1>
            <p class="lead">Oops! The page you're looking for doesn't exist.</p>
            <router-link to="/" class="btn btn-primary mt-4">Go Home</router-link>
        </div>
    </div>
</template>
<style scoped>
    .container {
        position: relative;
    }

    @keyframes containerAfter {
        0% {
            transform: translate(-50%, -50%) scale(1) rotate(0deg);
            top: 10%;
            left: 20%;
        }

        25% {
            transform: translate(-50%, -50%) scale(1.2) rotate(15deg);
            top: 30%;
            left: 40%;
        }

        50% {
            transform: translate(-50%, -50%) scale(0.8) rotate(-15deg);
            top: 50%;
            left: 60%;
        }

        75% {
            transform: translate(-50%, -50%) scale(1.1) rotate(5deg);
            top: 35%;
            left: 50%;
        }

        100% {
            transform: translate(-50%, -50%) scale(1) rotate(0deg);
            top: 45%;
            left: 70%;
        }
    }

    @keyframes containerBefore {
        0% {
            transform: translate(-50%, -50%) scale(1) rotate(0deg);
            top: 50%;
            left: 20%;
        }

        25% {
            transform: translate(-50%, -50%) scale(1.3) rotate(-20deg);
            top: 60%;
            left: 40%;
        }

        50% {
            transform: translate(-50%, -50%) scale(0.7) rotate(25deg);
            top: 40%;
            left: 70%;
        }

        75% {
            transform: translate(-50%, -50%) scale(1.2) rotate(-10deg);
            top: 30%;
            left: 50%;
        }

        100% {
            transform: translate(-50%, -50%) scale(1) rotate(0deg);
            top: 35%;
            left: 30%;
        }
    }

    .container::after {
        content: "🤔";
        filter: blur(2px);
        position: absolute;
        left: 20%;
        z-index: -1;
        top: 10%;
        font-size: 150px;

        animation: containerAfter 2s alternate-reverse infinite;
    }

    .container::before {
        content: "🥲";
        filter: blur(2px);
        position: absolute;
        left: 20%;
        z-index: -1;
        top: 10%;
        font-size: 150px;
        animation: containerBefore 3s alternate-reverse infinite;
    }

    /* Добавляем простую анимацию */
    .bounce {
        display: inline-block;
        animation: bounce 1.5s infinite;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-30px);
        }

        60% {
            transform: translateY(-15px);
        }
    }
</style>