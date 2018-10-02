<template>
    <div id="viewer">
        <select v-model="status_filter" @change="filter" id="status-filter">
            <option v-for="status in status_filter_options" :value="status.value">{{ status.name }}</option>
        </select>
        <div id="header" class="row">
            <div class="col8">Error</div>
            <div class="col2">Last occurence</div>
            <div class="col1">Events</div>
            <div class="col1">Status</div>
        </div>
        <router-link class="whoops row" v-for="whoops in whoopses" v-bind:key="whoops.id" :to="{name:'show', params: {id: whoops.id}}">
            <div class="col8">
                <span class="error ellipsis">{{ whoops.message }}</span>
                <span class="exception ellipsis">{{ whoops.exception_class }}</span>
            </div>
            <div class="col2 render-timeago" :datetime="whoops.last_occurred_at">{{ whoops.last_occurred_at }}</div>
            <div class="col1">{{ whoops.occurrences_count }}</div>
            <div class="col1">{{ status_name(whoops.status) }}</div>
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
                this.filter();
            });
        },
        updated() {
            window.timeago().render(document.querySelectorAll('.render-timeago'));
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
