<template>
  <div class="buc-home-full">
    <div class="buc-home-content">
      <h1 class="buc-home-title">Petit Buc</h1>
      <div class="buc-home-actions">
        <router-link to="/create">
          <button class="buc-home-btn">Créer une partie</button>
        </router-link>
        <router-link to="/join">
          <button class="buc-home-btn">Rejoindre une partie</button>
        </router-link>
      </div>
      <div class="buc-home-listzone">
        <h2 class="buc-home-listtitle">Parties en cours</h2>
        <ul class="buc-home-list">
          <li v-for="game in games" :key="game.id" class="buc-home-listitem">
            <div class="buc-home-gameinfo">
              <span class="buc-home-gamecode">Code : <b>{{ game.code }}</b></span>
              <span class="buc-home-gameid">ID : {{ game.id }}</span>
            </div>
            <router-link :to="`/game/${game.id}`">
              <button class="buc-home-listbtn">Voir la salle</button>
            </router-link>
          </li>
        </ul>
        <div v-if="loading" class="buc-home-loading">Chargement...</div>
        <div v-if="error" class="buc-home-error">Erreur: {{ error }}</div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';

const games = ref([]);
const loading = ref(false);
const error = ref('');

onMounted(async () => {
  loading.value = true;
  try {
    const response = await fetch('/api/v1/games');
    if (!response.ok) throw new Error('API error');
    const data = await response.json();
    games.value = Array.isArray(data.data) ? data.data : [];
  } catch (e) {
    error.value = e.message;
  } finally {
    loading.value = false;
  }
});
</script>
<style scoped>
.buc-home-full {
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
.buc-home-content {
  width: 100%;
  max-width: 540px;
  margin: 0 auto;
  padding: 2rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
  align-items: center;
}
.buc-home-title {
  font-size: 3rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 1.5rem;
  color: #fff;
  letter-spacing: 2px;
  text-shadow: 0 2px 12px #000a;
}
.buc-home-actions {
  display: flex;
  gap: 2rem;
  margin-bottom: 2rem;
}
.buc-home-btn {
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
.buc-home-btn:hover {
  background: #c41d00;
  transform: scale(1.05);
}
.buc-home-listzone {
  width: 100%;
  background: #222226;
  border-radius: 16px;
  box-shadow: 0 0 32px #0008;
  padding: 2rem 1rem 1rem 1rem;
}
.buc-home-listtitle {
  text-align: center;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #fff;
}
.buc-home-list {
  list-style: none;
  padding: 0;
  margin: 0;
}
.buc-home-listitem {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #18181b;
  border-radius: 10px;
  margin-bottom: 1rem;
  padding: 1rem 1.5rem;
  box-shadow: 0 1px 8px #0004;
}
.buc-home-gameinfo {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}
.buc-home-gamecode {
  font-size: 1.1rem;
  color: #fff;
}
.buc-home-gameid {
  font-size: 0.95rem;
  color: #aaa;
}
.buc-home-listbtn {
  background: #f53003;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 0.7rem 1.5rem;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}
.buc-home-listbtn:hover {
  background: #c41d00;
  transform: scale(1.05);
}
.buc-home-loading {
  color: #f53003;
  text-align: center;
  margin-top: 2rem;
}
.buc-home-error {
  color: #ff4433;
  text-align: center;
  margin-top: 2rem;
}
</style>
