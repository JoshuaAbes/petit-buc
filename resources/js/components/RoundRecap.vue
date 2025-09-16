<template>
  <div>
    <h2>Récapitulatif de la manche</h2>
    <div v-if="loading">Chargement du récapitulatif...</div>
    <div v-if="error" style="color:red;">Erreur: {{ error }}</div>
    <div v-if="recap">
      <h3>Lettre : {{ recap.letter }}</h3>
      <div v-for="cat in recap.categories" :key="cat.category" class="mb-4">
        <h4>Thème : {{ cat.category }}</h4>
        <table>
          <thead>
            <tr>
              <th>Joueur</th>
              <th>Réponse</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="player in cat.players" :key="player.player">
              <td>{{ player.player }}</td>
              <td>{{ player.answer || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, watch } from 'vue';
const props = defineProps({
  gameId: Number,
  roundId: Number,
});
const recap = ref(null);
const loading = ref(false);
const error = ref('');
async function fetchRecap() {
  loading.value = true;
  error.value = '';
  try {
    const res = await fetch(`/api/v1/games/${props.gameId}/rounds/${props.roundId}/recap`);
    if (!res.ok) throw new Error('Impossible de charger le récapitulatif');
    const data = await res.json();
    recap.value = data;
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
}
onMounted(fetchRecap);
watch(() => props.roundId, fetchRecap);
</script>
<style scoped>
table {
  border-collapse: collapse;
  width: 100%;
}
th, td {
  border: 1px solid #ccc;
  padding: 4px 8px;
}
th {
  background: #f5f5f5;
}
</style>
