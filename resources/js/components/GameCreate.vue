<template>
  <div class="buc-create-full">
    <div class="buc-create-content">
      <h1 class="buc-create-title">Créer une partie</h1>
      <form @submit.prevent="createGame" class="buc-create-form">
        <input v-model="name" type="text" placeholder="Nom de la partie" required class="buc-create-input" />
        <button type="submit" class="buc-create-btn">Créer</button>
      </form>
      <div v-if="loading" class="buc-create-loading">Création en cours...</div>
      <div v-if="error" class="buc-create-error">Erreur: {{ error }}</div>
      <div v-if="success" class="buc-create-success">Partie créée ! Code: {{ gameCode }}</div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const name = ref('');
const loading = ref(false);
const error = ref('');
const success = ref(false);
const gameCode = ref('');
const router = useRouter();

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
    // Redirection directe vers la salle de jeu créée
    router.push(`/game/${data.data.id}`);
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
}
</script>
<style scoped>
.buc-create-full {
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
.buc-create-content {
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
.buc-create-title {
  font-size: 2.3rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 1.5rem;
  color: #fff;
  letter-spacing: 1px;
  text-shadow: 0 2px 12px #000a;
}
.buc-create-form {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
  align-items: center;
}
.buc-create-input {
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
.buc-create-btn {
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
.buc-create-btn:hover {
  background: #c41d00;
  transform: scale(1.05);
}
.buc-create-loading {
  color: #f53003;
  text-align: center;
  margin-top: 1.5rem;
}
.buc-create-error {
  color: #ff4433;
  text-align: center;
  margin-top: 1.5rem;
}
.buc-create-success {
  color: #00e676;
  text-align: center;
  margin-top: 1.5rem;
}
</style>
