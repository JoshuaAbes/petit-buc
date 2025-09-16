<template>
  <div>
    <h2>Rejoindre une partie</h2>
    <form @submit.prevent="joinGame">
      <input v-model="playerName" type="text" placeholder="Nom du joueur" required />
      <input v-model="gameCode" type="text" placeholder="Code de la partie" required />
      <button type="submit">Rejoindre</button>
    </form>
    <div v-if="loading">Rejoindre en cours...</div>
    <div v-if="error" style="color:red;">Erreur: {{ error }}</div>
    <div v-if="success" style="color:green;">Joueur ajouté à la partie !</div>
  </div>
</template>
<script setup>
import { ref } from 'vue';

const playerName = ref('');
const gameCode = ref('');
const loading = ref(false);
const error = ref('');
const success = ref(false);

async function joinGame() {
  loading.value = true;
  error.value = '';
  success.value = false;
  try {
    // Récupère l'id du jeu via l'API
    const res = await fetch('/api/v1/games');
    const data = await res.json();
    const games = Array.isArray(data.data) ? data.data : [];
    const game = games.find(g => g.code === gameCode.value);
    if (!game) throw new Error('Code de partie invalide');

    // Requête pour rejoindre la partie
    const response = await fetch(`/api/v1/games/${game.id}/players`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ name: playerName.value }),
    });
    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'API error');
    }
    success.value = true;
    playerName.value = '';
    gameCode.value = '';
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
}
</script>
