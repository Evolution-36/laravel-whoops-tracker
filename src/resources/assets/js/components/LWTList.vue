<template>
    <div id="lwt-viewer">
        <select v-model="status_filter" @change="filter" id="lwt-status-filter">
            <option value="0">Open</option>
            <option value="1">Busy</option>
            <option value="2">Closed</option>
        </select>
        <div id="lwt-header" class="lwt-row">
            <div class="lwt-8col">Error</div>
            <div class="lwt-2col">Last occurence</div>
            <div class="lwt-1col">Events</div>
            <div class="lwt-1col">Status</div>
        </div>
        <router-link class="lwt-whoops lwt-row" v-for="whoops in whoopses" v-bind:key="whoops.id" :to="'/' + whoops.id">
            <div class="lwt-8col">
                <span class="error ellipsis">{{ whoops.message }}</span>
                <span class="ellipsis">{{ whoops.exception_class }}</span>
            </div>
            <div class="lwt-2col lwt-render-timeago" :datetime="whoops.last_occurred_at">{{ whoops.last_occurred_at }}</div>
            <div class="lwt-1col">{{ whoops.occurrences_count }}</div>
            <div class="lwt-1col">{{ status_name(whoops.status) }}</div>
        </router-link>
    </div>
</template>

<script>
    import status from '../mixins/status';
    export default {
        mixins: [
          status
        ],
        data () {
            return {
                whoopses: [],
                all_whoopses: [],
                status_filter: 0
            }
        },
        mounted() {
            axios.get('/lwt/whoopses').then(response => {
                this.all_whoopses = response.data;
                this.whoopses = this.all_whoopses;
            });
        },
        updated() {
            window.timeago().render(document.querySelectorAll('.lwt-render-timeago'));
        },
        methods: {
            filter: function() {
                console.log(this.status_filter);
                this.whoopses = this.all_whoopses.filter(whoops => {
                    console.log(whoops.status);
                    return whoops.status === parseInt(this.status_filter);
                });
            }
        }
    }
</script>
