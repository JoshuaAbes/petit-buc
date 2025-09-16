<template>
  <div>
    <h1 style="text-align:center;margin:2rem 0;">Petit Buc Frontend OK</h1>
    <nav style="display:flex;justify-content:center;gap:2rem;margin-bottom:2rem;">
      <router-link to="/">Accueil</router-link>
      <router-link to="/create">Créer une partie</router-link>
      <router-link to="/join">Rejoindre une partie</router-link>
    </nav>
    <router-view />
  </div>
</template>
<script setup>
// Vue Router sera injecté dans main.js/app.js
</script>
