<template>
    <div id="whoops-viewer">
        <h4>Whoopses</h4>
        <table id="whoops-table">
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Exception class</th>
                    <th>Location</th>
                    <th>Occurrences</th>
                    <th>Last occurrence</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="whoops in whoopses">
                    <td>{{ whoops.message }}</td>
                    <td>{{ whoops.exception_class }}</td>
                    <td>{{ file_name(whoops.file) }}:{{ whoops.line }}</td>
                    <td>{{ whoops.occurrences_count }}</td>
                    <td>{{ whoops.last_occurred_at }}</td>
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
            });
        },
        methods: {
            file_name: function(file) {
                let parts = file.split('/');
                return parts[parts.length-2] + '/' + parts[parts.length-1];
            }
        }
    }
</script>
