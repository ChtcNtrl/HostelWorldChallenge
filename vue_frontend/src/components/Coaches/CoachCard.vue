<script setup>
/**
 * This component represents a card displaying coach information.
 * It receives a `coach` object as a prop and displays its details.
 *
 * Expected object shape:
 * - coach: {
 *     name: String,
 *     experience: Number,
 *     rate: Number,
 *     location: String,
 *     joined: String
 *   }
 */

defineProps({
  coach: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <div class="coach">
    <h2>{{coach.name}}</h2>
    <p>Experience: {{coach.experience}} {{ coach.experience === 1 ? 'year' : 'years' }}</p>
    <p>Rate: ${{coach.rate}}/hour</p>
    <p>Location: {{coach.location}}</p>
    <p>Joined: {{coach.joined}}</p>
  </div>
</template>

<style scoped>
.coach {
  border: 1px solid #ddd;
  padding: 1rem;
  background-color: #fff;
}
</style>
