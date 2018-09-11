<template>
    <div id="lwt-shower">
        <div class="lwt-whoops" v-if="whoops">
            <div class="lwt-row">
                <div class="lwt-8col">
                    <h5>{{ whoops.exception_class }}</h5>
                    <p class="error-long">{{ whoops.message }}</p>
                </div>
                <div class="lwt-4col">
                    <table>
                        <tr>
                            <td>Last occurrence</td>
                            <td class="lwt-render-timeago" :datetime="whoops.last_occurred_at">{{ whoops.last_occurred_at }}</td>
                        </tr>
                        <tr>
                            <td>Occurrences</td>
                            <td>{{ whoops.occurrences_count }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ status_name(whoops.status) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="lwt-row lwt-12col">
                <select v-model="selected_occurrence_id" @change="show_occurrence" id="lwt-status-filter">
                    <option v-for="occurrence in whoops.lwt_occurrences" :value="occurrence.id">{{ occurrence.occurred_at }}</option>
                </select>
            </div>

            <div class="lwt-row" v-if="occurrence">
                <div class="lwt-8col">
                    <pre class="prepost">{{ occurrence.log.fileContext.pre.join("\n") }}</pre>
                    <pre class="errorline">{{ occurrence.log.fileContext.self }}</pre>
                    <pre class="prepost">{{ occurrence.log.fileContext.post.join("\n") }}</pre>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                whoops: undefined,
                selected_occurrence_id: 1,
                occurrence: undefined
            }
        },
        mounted() {
            axios.get('/lwt/whoops/' + this.$route.params.id).then(response => {
                this.whoops = response.data;
                console.log(response.data);
                this.selected_occurrence_id = this.whoops.lwt_occurrences[0].id;
                axios.get('/lwt/occurrence/' + this.selected_occurrence_id).then(response => {
                    this.occurrence = response.data;
                    console.log(response.data);
                });
            });
        },
        updated() {
            window.timeago().render(document.querySelectorAll('.lwt-render-timeago'));
        },
        methods: {
            status_name: function(status) {
                return status === 0 ? 'Open' : (status === 1 ? 'Busy' : 'Closed');
            },
            show_occurrence: function() {
                console.log(this.selected_occurrence_id);
            }
        }
    }
</script>