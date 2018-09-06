<template>
    <div id="whoops-viewer">
        <h4>Whoopses</h4>
        <table id="whoops-table">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Exception class</th>
                    <th>Location</th>
                    <th>Occurences</th>
                    <th>Last occurence</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="whoops in whoopses">
                    <td>{{ whoops.message }}</td>
                    <td>{{ whoops.exception_class }}</td>
                    <td>{{ whoops.file }}:{{ whoops.line }}</td>
                    <td>{{ whoops.lwt_occurrences.length }}</td>
                    <td>{{ last_occurred_at(whoops.lwt_occurrences) }}</td>
                    <td>{{ whoops.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                whoopses: []
            }
        },
        mounted() {
            axios.get('/lwt/whoopses').then(response => {
                this.whoopses = response.data;
                console.log(this.whoopses);
            });
        },
        methods: {
            last_occurred_at: function(lwt_occurrences){
                return lwt_occurrences.map(
                    function(occurrence) {
                        return occurrence.occurred_at;
                    }
                ).sort()[0]
            }
        }
    }
</script>
