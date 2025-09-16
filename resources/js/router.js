import { createRouter, createWebHistory } from 'vue-router';
import GameList from './components/GameList.vue';
import GameCreate from './components/GameCreate.vue';
import GameJoin from './components/GameJoin.vue';
import GameRoom from './components/GameRoom.vue';

const routes = [
  { path: '/', name: 'Home', component: GameList },
  { path: '/create', name: 'CreateGame', component: GameCreate },
  { path: '/join', name: 'JoinGame', component: GameJoin },
  { path: '/game/:id', name: 'GameRoom', component: GameRoom, props: true },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
