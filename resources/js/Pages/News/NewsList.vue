<template>
  <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-4 xl:px-0">
      <div class="mx-auto max-w-2xl lg:mx-0">
        <p class="text-5xl font-bold tracking-tight text-gray-900">Новости</p>
      </div>

      <!-- Если есть новости -->
      <div v-if="posts.data && posts.data.length > 0"
           class="mx-auto my-0 mt-10 grid w-full grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 md:grid-cols-2 xl:grid-cols-3"
      >
        <NewsItem v-for="post in posts.data" :key="post.id" :post="post"/>
      </div>

      <!-- Использование нашего компонента -->
      <EmptyState
          v-else
          title="На данный момент новостей нет"
          description="Мы готовим для вас актуальные материалы. Загляните сюда немного позже."
          buttonText="На главную"
          link="/"
      />
    </div>
  </div>
</template>

<script setup>
import NewsItem from "@/Pages/News/NewsItem.vue";
import EmptyState from "@/Components/EmptyState.vue"; // Импорт компонента

const props = defineProps({
  posts: { type: Object, default: () => ({ data:[] }) }
});
</script>
