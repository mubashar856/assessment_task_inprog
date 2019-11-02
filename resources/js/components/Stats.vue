<template>
    <div>
        <br/>
        <br/>
        <div v-for="match in matches" :key="match.matchId">
            <div class="match-section">
                <div class="match-heading">
                    Match with id {{match.matchId}} started between <b>{{match.team1}}</b> and <b>{{match.team2}}</b>
                    <table class="table table-bordered">
                        <tbody>
                        <tr v-for="stat in filterBallStats(match.matchId)">
                            <th>{{stat.batsman}}</th>
                            <td>{{stat.scoreType}}</td>
                            <td>{{stat.score}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
        </div>
    </div>
</template>

<script>
    import _ from 'lodash'

    export default {
        data() {
            return {
                matches: [],
                matchStats: [],
                ballStats: []
            }
        },
        methods: {
            filterBallStats: function (matchId) {
                return _.filter(this.ballStats, stat => stat.matchId === matchId)
            }
        },
        created() {
            Echo.channel('cric-stats')
                .listen('MatchStartEvent', (e) => {
                    this.matches.push({
                        matchId: e.matchId,
                        team1: e.team1,
                        team2: e.team2
                    })
                })
            Echo.channel('cric-stats').listen('BallStatsEvent', (e) => {
                this.ballStats.push({
                    matchId: e.matchId,
                    bowler: e.bowler,
                    batsman: e.batsman,
                    scoreType: e.scoreType,
                    score: e.score
                })
            });
            Echo.channel('cric-stats').listen('MatchStatsEvent', (e) => {
                this.matchStats.push({
                    matchId: e.matchId,
                    team1Score: e.team1Score,
                    team1Wickets: e.team1Wickets,
                    team1Overs: e.team1Overs,
                    team2Score: e.team2Score,
                    team2Wickets: e.team2Wickets,
                    team2Overs: e.team2Overs
                })
            });
        }
    }
</script>
<style>
    .match-section {
        width: 80%;
        margin: auto;
        border: solid 3px #888;
    }

    .match-heading {
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 500;
    }
</style>