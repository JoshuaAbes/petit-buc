<template>
    <div v-if="round" class="buc-manche-full">
        <div class="buc-manche-content">
            <div class="buc-manche-header">
                <span class="buc-manche-lettre">Lettre {{ round.letter }}</span>
                <span class="buc-manche-round">| Round {{ round.number }}</span>
            </div>
            <ol class="buc-manche-cats">
                <li v-for="(cat, idx) in round.categories" :key="cat.id">
                    <span class="buc-manche-num">{{ idx + 1 }}.</span>
                    <span class="buc-manche-txt">{{ cat.name }}</span>
                    <div class="buc-manche-dotline"></div>
                </li>
            </ol>
        </div>
    </div>
        <div class="buc-bg">
            <h2 class="buc-title">Salle de jeu</h2>
            <form @submit.prevent="fetchGame" class="buc-form">
                <input
                    v-model="gameCode"
                    type="text"
                    placeholder="Code de la partie"
                    required
                    class="buc-input"
                />
                <button type="submit" class="buc-btn">Afficher la salle</button>
            </form>
            <div v-if="loading" class="buc-loading">Chargement...</div>
            <div v-if="error" class="buc-error">Erreur: {{ error }}</div>
            <div v-if="game">
                <div class="buc-room-info">
                    <div class="buc-room-row"><b>Partie :</b> <span>{{ game.code }} (ID: {{ game.id }})</span></div>
                    <div class="buc-room-row"><b>Statut :</b> <span>{{ game.status }}</span></div>
                    <div class="buc-room-row"><b>Joueurs :</b>
                        <ul class="buc-room-players">
                            <li v-for="player in players" :key="player.id">{{ player.name }}</li>
                        </ul>
                    </div>
                </div>
                <button @click="startRound" :disabled="startingRound || !game" class="buc-btn" style="margin-bottom:1rem;">Démarrer</button>
                <div v-if="startingRound" class="buc-loading">Démarrage du round...</div>
                <div v-if="round" class="buc-round">
                    <h2 class="buc-round-title">Lettre {{ round.letter }} | Round {{ round.number }}</h2>
                    <ol class="buc-round-cats">
                        <li v-for="(cat, idx) in round.categories" :key="cat.id">
                            {{ idx + 1 }}. {{ cat.name }}
                        </li>
                    </ol>
                </div>
                <Scoreboard :gameId="game.id" />
                <div v-if="round && round.status === 'finished'">
                    <RoundRecap :gameId="game.id" :roundId="round.id" />
                </div>
            </div>
        </div>
</template>
<script setup>
import { ref } from "vue";
import Scoreboard from "./Scoreboard.vue";
import RoundRecap from "./RoundRecap.vue";

const gameCode = ref("");
const game = ref(null);
const players = ref([]);
const round = ref(null);
const loading = ref(false);
const error = ref("");
const startingRound = ref(false);
async function startRound() {
    if (!game.value) return;
    startingRound.value = true;
    try {
        const response = await fetch(
            `/api/v1/games/${game.value.id}/rounds/start`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            }
        );
        if (!response.ok) throw new Error("Erreur lors du démarrage du round");
        // Recharge le round courant
        const resRound = await fetch(
            `/api/v1/games/${game.value.id}/rounds/current`
        );
        if (resRound.ok) {
            const dataRound = await resRound.json();
            round.value = dataRound.data || null;
        }
    } catch (e) {
        error.value = e.message;
    } finally {
        startingRound.value = false;
    }
}

async function fetchGame() {
    loading.value = true;
    error.value = "";
    game.value = null;
    players.value = [];
    round.value = null;
    try {
        // Récupère l'id du jeu via l'API
        const res = await fetch("/api/v1/games");
        const data = await res.json();
        const games = Array.isArray(data.data) ? data.data : [];
        const foundGame = games.find((g) => g.code === gameCode.value);
        if (!foundGame) throw new Error("Code de partie invalide");
        game.value = foundGame;

        // Récupère les joueurs
        const resPlayers = await fetch(
            `/api/v1/games/${game.value.id}/players`
        );
        const dataPlayers = await resPlayers.json();
        players.value = Array.isArray(dataPlayers.data) ? dataPlayers.data : [];

        // Récupère le round courant
        const resRound = await fetch(
            `/api/v1/games/${game.value.id}/rounds/current`
        );
        if (resRound.ok) {
            const dataRound = await resRound.json();
            round.value = dataRound.data || null;
        }
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
}
</script>
<style scoped>
.buc-manche-full {
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
.buc-manche-content {
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
    padding: 2rem 1rem;
    background: transparent;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}
.buc-manche-header {
    font-size: 2.2rem;
    font-weight: bold;
    text-align: left;
    margin-bottom: 2rem;
    color: #fff;
    letter-spacing: 1px;
}
.buc-manche-lettre {
    color: #fff;
}
.buc-manche-round {
    color: #fff;
    margin-left: 1rem;
}
.buc-manche-cats {
    list-style: none;
    padding: 0;
    margin: 0;
}
.buc-manche-cats li {
    font-size: 1.3rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    margin-bottom: 1.2rem;
    position: relative;
    flex-direction: column;
}
.buc-manche-num {
    color: #f53003;
    font-weight: bold;
    font-size: 1.5rem;
    margin-right: 0.7rem;
    align-self: flex-start;
}
.buc-manche-txt {
    color: #fff;
    font-size: 1.2rem;
    margin-left: 0.5rem;
    align-self: flex-start;
}
.buc-manche-dotline {
    width: 100%;
    border-bottom: 2px dotted #fff;
    margin-top: 0.5rem;
    margin-bottom: 0.2rem;
    opacity: 0.5;
}
.buc-manche-cats {
    list-style: none;
    padding: 0;
    margin: 0;
    padding-left: 1.2rem;
}
</style>