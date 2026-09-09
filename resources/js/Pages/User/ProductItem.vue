<template>
    <article class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-3 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-gray-300 hover:shadow-lg">
        <a :href="`/catalog/product/${detail.dt_invoice}`" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-green-700 focus-visible:ring-offset-2 rounded-xl">
            <div class="flex aspect-[4/3] items-center justify-center overflow-hidden rounded-xl bg-gray-50">
            <img
                src="/no-photo--lg.png"
                    :alt="`Изображение товара ${editTitle(detail.dt_typec)} ${detail.dt_invoice}`"
                    class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-[1.03]"
            />
            </div>

            <div class="p-3 pb-4">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-lg font-bold leading-7 text-gray-900 transition-colors group-hover:text-green-700">
                        {{ editTitle(detail.dt_typec) }} {{ detail.dt_invoice }}
                    </h3>
                    <span v-if="detail.oem" class="shrink-0 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                        {{ detail.oem }}
                    </span>
                </div>
                <dl class="mt-3 space-y-1.5 text-base text-gray-500">
                    <div class="flex gap-1.5">
                        <dt>Артикул:</dt>
                        <dd class="font-medium text-gray-700">{{ detail.dt_cargo || '—' }}</dd>
                    </div>
                    <div class="flex gap-1.5">
                        <dt>Бренд:</dt>
                        <dd class="font-medium text-gray-700">{{ detail.fr_code || '—' }}</dd>
                    </div>
                </dl>
            </div>
        </a>
    </article>
</template>

<script setup>
    import {editDetailTitle} from "@/Services/TitleService";

    defineProps({
        detail: Object,
    })

    const editTitle = (res) => editDetailTitle(res);
</script>
