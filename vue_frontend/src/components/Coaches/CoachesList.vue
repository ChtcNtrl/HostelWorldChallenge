<script setup>
import { computed, onMounted, ref } from "vue";
import CoachCard from "@/components/Coaches/CoachCard.vue";

/**
 * Ref to store the list of coaches.
 * @type {Ref<Array>}
 */
const coaches = ref([]);

/**
 * Ref to store the search term.
 * @type {Ref<string>}
 */
const search = ref("");

/**
 * Ref to store the sort order.
 * @type {Ref<string>}
 */
const sort = ref("rate-asc");

/**
 * Computed property to generate the API URL with query parameters.
 * @returns {URL} The API URL with search and sort parameters.
 */
const apiUrl = computed(() => {
  const url = new URL(import.meta.env.VITE_API_BASE_URL + 'coaches.php');
  url.searchParams.append('sort', sort.value);

  if (search.value) {
    url.searchParams.append('search', search.value);
  }

  return url;
});

/**
 * Fetches the list of coaches from the API and updates the `coaches` ref.
 * @returns {Promise<void>}
 */
const fetchCoaches = async () => {
  const response = await fetch(apiUrl.value);
  coaches.value = await response.json();
};

/**
 * Fetch the coaches when the component is mounted.
 */
onMounted(fetchCoaches);
</script>

<template>
  <main>
    <header>
      <h1>SuperSpin Tennis Coaches</h1>
      <input v-model="search"
             @keyup="fetchCoaches"
             type="text" id="search" placeholder="Search by name or location">
      <select id="sort" v-model="sort" @change="fetchCoaches">
        <option value="rate-asc">Sort by hourly rate (ascending)</option>
        <option value="rate-desc">Sort by hourly rate (descending)</option>
      </select>
    </header>

    <div id="coach-list">
      <CoachCard v-for="coach in coaches" :key="coach.id" :coach="coach"/>
    </div>
  </main>
</template>

<style scoped>
#coach-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
  padding: 1rem;
}
</style>
