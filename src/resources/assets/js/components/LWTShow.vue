<template>
    <div id="shower">
        <div class="whoops" v-if="whoops">
            <div class="row">
                <div class="col8">
                    <h5>{{ whoops.exception_class }}</h5>
                    <p class="error-long">{{ whoops.message }}</p>
                </div>
                <div class="col4">
                    <table>
                        <tr>
                            <td>Last occurrence</td>
                            <td class="render-timeago" :datetime="whoops.last_occurred_at">{{
                                whoops.last_occurred_at }}
                            </td>
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

            <div class="row col12">
                <select v-model="selected_occurrence_id" @change="show_occurrence"
                        id="status-filter">
                    <option v-for="occurrence in whoops.lwt_occurrences" :value="occurrence.id">{{
                        occurrence.occurred_at }}
                    </option>
                </select>
            </div>

            <div v-if="occurrence">
                <div class="tabs row">
                    <button class="tab active" id="tab-default"
                            v-on:click="open_tab($event, 'trace-tab')">
                        Stack trace
                    </button>
                    <button class="tab" v-on:click="open_tab($event, 'session-tab')">
                        Session info
                    </button>
                    <button class="tab" v-on:click="open_tab($event, 'config-tab')">
                        Config info
                    </button>
                </div>

                <div class="tab-content active" id="trace-tab">
                    <p class="toggle-input">
                        Application only
                        <label class="toggle">
                            <input type="checkbox" checked v-on:click="toggle_app_only()">
                            <span class="slider"></span>
                        </label>
                    </p>
                    <div class="trace-spot" v-for="(spot, index) in occurrence.log.trace"
                         v-show="spot.inApp || !app_only">
                        <button class="open-spot" v-on:click="open_spot($event)">
                            <span>{{ index }}</span>{{ spot.file }}:{{ spot.line }}
                        </button>
                        <div class="row trace-context">
                            <div class="col12">
                                <div v-if="spot.context">
                                    <pre :class="'prettyprint linenums:' + (spot.line - spot.context.pre.length)">{{ spot.context.pre.join("\n") }}</pre>
                                    <pre :class="'errorline prettyprint linenums:' + spot.line">{{ spot.context.self }}</pre>
                                    <pre :class="'prettyprint linenums:' + (parseInt(spot.line) + 1)">{{ spot.context.post.join("\n") }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="session-tab">
                    Datetime: {{ occurrence.log.datetime.date }}
                </div>
                <div class="tab-content" id="config-tab">
                    Config info
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import status from '../mixins/status';

    export default {
        mixins: [
            status
        ],
        data() {
            return {
                whoops: undefined,
                selected_occurrence_id: 1,
                occurrence: undefined,
                app_only: true
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
            window.timeago().render(document.querySelectorAll('.render-timeago'));
            PR.prettyPrint();
        },
        methods: {
            show_occurrence: function () {
                axios.get('/lwt/occurrence/' + this.selected_occurrence_id).then(response => {
                    this.occurrence = response.data;
                    console.log(response.data);
                });
            },
            open_tab: function (event, tab) {
                Array.prototype.forEach.call(document.getElementsByClassName('tab-content'), function (tab_content) {
                    tab_content.classList.remove('active');
                });

                document.getElementById(tab).classList.add('active');

                Array.prototype.forEach.call(document.getElementsByClassName('tab'), function (tab) {
                    tab.classList.remove('active');
                });
                event.currentTarget.classList.add('active');
            },
            toggle_app_only: function () {
                this.app_only = !this.app_only;
            },
            open_spot: function (event) {
                event.target.classList.toggle("active");

                let panel = event.target.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            }
        }
    }
</script>