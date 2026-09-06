<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-green-50 via-white to-emerald-100 px-4 py-8"
    >
        <div>
            <spa-link href="/">
                <img
                    src="/logo2.png"
                    class="h-8"
                    alt="AmiAvto Logo"
                />
            </spa-link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white border border-green-100 shadow-lg overflow-hidden rounded-xl"
        >
            <slot />
        </div>
    </div>
</template>
