<template>
  <div class="buc-join-full">
    <div class="buc-join-content">
      <h1 class="buc-join-title">Rejoindre une partie</h1>
      <form @submit.prevent="joinGame" class="buc-join-form">
        <input v-model="playerName" type="text" placeholder="Nom du joueur" required class="buc-join-input" />
        <input v-model="gameCode" type="text" placeholder="Code de la partie" required class="buc-join-input" />
        <button type="submit" class="buc-join-btn">Rejoindre</button>
      </form>
      <div v-if="loading" class="buc-join-loading">Rejoindre en cours...</div>
      <div v-if="error" class="buc-join-error">Erreur: {{ error }}</div>
      <div v-if="success" class="buc-join-success">Joueur ajouté à la partie !</div>
    </div>
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
<style scoped>
.buc-join-full {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: #18181b;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.buc-join-content {
  width: 100%;
  max-width: 420px;
  margin: 0 auto;
  padding: 2.5rem 1.5rem;
  background: #222226;
  border-radius: 18px;
  box-shadow: 0 0 32px #0008;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2rem;
}
.buc-join-title {
  font-size: 2.3rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 1.5rem;
  color: #fff;
  letter-spacing: 1px;
  text-shadow: 0 2px 12px #000a;
}
.buc-join-form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
  align-items: center;
}
.buc-join-input {
  background: #18181b;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 1rem 1.5rem;
  font-size: 1.1rem;
  width: 100%;
  margin-bottom: 0.2rem;
  box-shadow: 0 1px 8px #0004;
}
.buc-join-btn {
  background: #f53003;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 1rem 2.2rem;
  font-size: 1.2rem;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 0 2px 12px #0006;
  transition: background 0.2s, transform 0.2s;
}
.buc-join-btn:hover {
  background: #c41d00;
  transform: scale(1.05);
}
.buc-join-loading {
  color: #f53003;
  text-align: center;
  margin-top: 1.5rem;
}
.buc-join-error {
  color: #ff4433;
  text-align: center;
  margin-top: 1.5rem;
}
.buc-join-success {
  color: #00e676;
  text-align: center;
  margin-top: 1.5rem;
}
</style>
