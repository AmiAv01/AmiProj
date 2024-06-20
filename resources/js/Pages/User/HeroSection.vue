<template>
    <section class="bg-white dark:bg-gray-900">
        <div
            class="grid max-w-screen-xl py-8 mx-auto lg:gap-8 xl:gap-6 lg:py-16 lg:grid-cols-12"
        >
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1
                    class="max-w-3xl mb-12 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white"
                >
                    Большой выбор стартеров, генераторов и других автозапчастей
                </h1>
                <p
                    class="max-w-2xl mb-6 font-normal text-gray-500 lg:mb-8 md:text-lg lg:text-2xl dark:text-gray-400"
                >
                    Узнайте больше о наших запчастях
                </p>
                <a
                    href="/catalog/starters"
                    class="inline-flex items-center justify-center px-16 py-6 mr-3 text-xl font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-900 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
                >
                    Стартеры
                    <svg
                        class="w-5 h-5 ml-2 -mr-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </a>
                <a
                    href="/catalog/generators"
                    class="inline-flex cursor-pointer items-center justify-center px-16 py-6 text-xl font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
                >
                    Генераторы
                    <svg
                        class="w-5 h-5 ml-2 -mr-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                </a>
            </div>
            <div class="hidden ml-12 lg:col-span-5 lg:flex">
                <img src="../../../../public/starters.jpg" alt="mockup" />
            </div>
        </div>
    </section>
</template>
