<template>
  <div>
    <h2>Créer une partie</h2>
    <form @submit.prevent="createGame">
      <input v-model="name" type="text" placeholder="Nom de la partie" required />
      <button type="submit">Créer</button>
    </form>
    <div v-if="loading">Création en cours...</div>
    <div v-if="error" style="color:red;">Erreur: {{ error }}</div>
    <div v-if="success" style="color:green;">Partie créée ! Code: {{ gameCode }}</div>
  </div>
</template>
<script setup>
import { ref } from 'vue';

const name = ref('');
const loading = ref(false);
const error = ref('');
const success = ref(false);
const gameCode = ref('');

async function createGame() {
  loading.value = true;
  error.value = '';
  success.value = false;
  try {
    const response = await fetch('/api/v1/games', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ name: name.value }),
    });
    if (!response.ok) throw new Error('API error');
    const data = await response.json();
    gameCode.value = data.data.code;
    success.value = true;
    name.value = '';
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
}
</script>
