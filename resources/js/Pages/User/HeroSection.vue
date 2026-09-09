<template>
    <section class="bg-white px-4 py-10 md:px-5 sm:py-14 lg:px-6 lg:py-16">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-3xl border border-gray-100 bg-gradient-to-br from-gray-50 via-white to-green-50 px-6 py-10 shadow-sm sm:px-10 sm:py-12 lg:px-14 lg:py-16">
            <div class="pointer-events-none absolute -right-24 -top-32 h-96 w-96 rounded-full bg-green-100/70 blur-3xl" aria-hidden="true"></div>

            <div class="relative grid items-center gap-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(340px,0.85fr)]">
                <div>
                    <p class="mb-4 text-sm font-bold uppercase tracking-[0.18em] text-green-700">
                        Запчасти для стартеров и генераторов
                    </p>
                    <h1 class="max-w-3xl text-4xl font-extrabold leading-[1.08] tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                        Большой выбор стартеров, генераторов и других автозапчастей
                    </h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-gray-600 sm:text-xl">
                        Подберём нужную деталь по артикулу, бренду или модели автомобиля.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 min-[420px]:flex-row">
                        <a
                            href="/catalog/starters"
                            class="inline-flex min-h-12 items-center justify-center rounded-lg bg-green-700 px-7 py-3.5 text-base font-semibold text-white transition-colors hover:bg-green-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2"
                        >
                            Стартеры
                            <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a
                            href="/catalog/generators"
                            class="inline-flex min-h-12 items-center justify-center rounded-lg border border-gray-300 bg-white px-7 py-3.5 text-base font-semibold text-gray-800 transition-colors hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                        >
                            Генераторы
                            <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="relative mx-auto flex w-full max-w-lg items-center justify-center lg:justify-end">
                    <div class="absolute h-64 w-64 rounded-full bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)] sm:h-80 sm:w-80" aria-hidden="true"></div>
                    <img
                        src="/starters.jpg"
                        class="relative z-10 w-full max-w-md object-contain mix-blend-multiply"
                        alt="Автомобильный стартер"
                    >
                </div>
            </div>
        </div>
    </section>
</template>
