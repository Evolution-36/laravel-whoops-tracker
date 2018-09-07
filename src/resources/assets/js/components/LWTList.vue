<template>
    <div id="whoops-viewer">
        <h4>Whoopses</h4>
        <div class="row lwt-whoops" v-for="whoops in whoopses">
            <div class="four columns">
                <h5>{{ whoops.message }}</h5>
                <h6>{{ whoops.exception_class }}</h6>
                <span class="render_timeago" :datetime="whoops.last_occurred_at">{{ whoops.last_occurred_at }}</span>
            </div>

            <p>{{ file_name(whoops.file) }}:{{ whoops.line }}</p>
            <p>{{ whoops.occurrences_count }}</p>

            <p>{{ whoops.status }}</p>
        </div>
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
        updated() {
            window.timeago().render(document.querySelectorAll('.render_timeago'));
        },
        methods: {
            file_name: function(file) {
                let parts = file.split('/');
                return parts[parts.length-2] + '/' + parts[parts.length-1];
            }
        }
    }
</script>
