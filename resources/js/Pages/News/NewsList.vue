<template>
  <section class="bg-gray-50 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 md:px-5 lg:px-6">
      <div>
        <p class="text-sm font-bold uppercase tracking-[0.18em] text-green-700">Актуальное</p>
        <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Новости</h2>
      </div>

      <div v-if="posts.data && posts.data.length > 0"
           class="mt-8 grid w-full grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3"
      >
        <NewsItem v-for="post in posts.data" :key="post.id" :post="post"/>
      </div>

      <EmptyState
          v-else
          title="На данный момент новостей нет"
          description="Мы готовим для вас актуальные материалы. Загляните сюда немного позже."
          buttonText="На главную"
          link="/"
      />
    </div>
  </section>
</template>

<script setup>
import NewsItem from "@/Pages/News/NewsItem.vue";
import EmptyState from "@/Components/EmptyState.vue";

defineProps({
  posts: { type: Object, default: () => ({ data:[] }) }
});
</script>
