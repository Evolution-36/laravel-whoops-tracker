<template>
    <div>
        <h4>Whoopses</h4>
        <table>
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Exception class</th>
                    <!--<th>Location</th>-->
                    <th>Occurences</th>
                    <th>Last occurence</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="whoops in whoopses">
                    <td>{{ whoops.message }}</td>
                    <td>{{ whoops.exception_class }}</td>
                    <!--<td>{{ whoops.file }}:{{ whoops.line }}</td>-->
                    <td>{{ whoops.lwt_occurrences.length }}</td>
                    <td>{{ whoops.lwt_occurrences.pop().occurred_at }}</td>
                    <td>{{ whoops.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        props: ['whoopsesJson'],
        computed: {
            whoopses: function () {
                return JSON.parse(this.whoopsesJson)
            }
        }
    }
</script>
