<template>
  <div>
    <h1 style="text-align:center;margin:2rem 0;">Petit Buc Frontend OK</h1>
    <GameList />
    <GameCreate />
    <GameJoin />
  </div>
</template>
<script setup>
import GameList from './GameList.vue';
import GameCreate from './GameCreate.vue';
import GameJoin from './GameJoin.vue';
</script>
