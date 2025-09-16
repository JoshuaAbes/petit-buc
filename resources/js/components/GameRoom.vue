<template>
  <div>
    <h2>Salle de jeu</h2>
    <PlayerList />
    <Round />
    <Scoreboard />
  </div>
</template>
<script setup>
import PlayerList from './PlayerList.vue';
import Round from './Round.vue';
import Scoreboard from './Scoreboard.vue';
</script>
