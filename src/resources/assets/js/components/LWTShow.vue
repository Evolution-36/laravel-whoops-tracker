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
                            <td class="lwt-render-timeago" :datetime="whoops.last_occurred_at">{{
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

            <div class="lwt-row lwt-12col">
                <select v-model="selected_occurrence_id" @change="show_occurrence"
                        id="lwt-status-filter">
                    <option v-for="occurrence in whoops.lwt_occurrences" :value="occurrence.id">{{
                        occurrence.occurred_at }}
                    </option>
                </select>
            </div>

            <div v-if="occurrence">
                <div class="lwt-tabs lwt-row">
                    <button class="lwt-tab" id="lwt-tab-default" v-on:click="open_tab($event, 'trace-tab')">Stack trace</button>
                    <button class="lwt-tab" v-on:click="open_tab($event, 'session-tab')">Session info</button>
                    <button class="lwt-tab" v-on:click="open_tab($event, 'config-tab')">Config info</button>
                </div>

                <div class="lwt-tab-content" id="trace-tab">
                    <button id="app-only" class="active" v-on:click="toggle_app_only($event)">Application only</button>
                    <div class="trace-spot" v-for="(spot, index) in occurrence.log.trace" v-if="spot.inApp || !app_only">
                        <button class="open-spot"><span>{{ index }}</span>{{ spot.file }}:{{ spot.line }}</button>
                        <div class="lwt-row trace-context">
                            <div class="lwt-12col">
                                <div v-if="spot.context">
                                    <pre
                                        :class="'prettyprint linenums:' + (spot.line - spot.context.pre.length)">{{ spot.context.pre.join("\n") }}</pre>
                                    <pre :class="'errorline prettyprint linenums:' + spot.line">{{ spot.context.self }}</pre>
                                    <pre
                                        :class="'prettyprint linenums:' + (parseInt(spot.line) + 1)">{{ spot.context.post.join("\n") }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lwt-tab-content" id="session-tab">Datetime: {{ occurrence.log.datetime.date }}</div>
                <div class="lwt-tab-content" id="config-tab">Config info</div>
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
            window.timeago().render(document.querySelectorAll('.lwt-render-timeago'));
            PR.prettyPrint();
            let trace = document.getElementsByClassName("open-spot");
            for (let i = 0; i < trace.length; i++) {
                trace[i].addEventListener("click", function () {
                    /* Toggle between adding and removing the "active" class,
                    to highlight the button that controls the panel */
                    this.classList.toggle("active");

                    /* Toggle between hiding and showing the active panel */
                    let panel = this.nextElementSibling;
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                    } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                    }
                });
            }

            document.getElementById('lwt-tab-default').click();
        },
        methods: {
            status_name: function (status) {
                return status === 0 ? 'Open' : (status === 1 ? 'Busy' : 'Closed');
            },
            show_occurrence: function () {
                axios.get('/lwt/occurrence/' + this.selected_occurrence_id).then(response => {
                    this.occurrence = response.data;
                    console.log(response.data);
                });
            },
            open_tab: function(event, tab) {
                Array.prototype.forEach.call(document.getElementsByClassName('lwt-tab-content'), function (tab_content) {
                    tab_content.style.display = "none";
                });

                document.getElementById(tab).style.display = "block";

                Array.prototype.forEach.call(document.getElementsByClassName('lwt-tab'), function( tab) {
                    tab.classList.remove('active');
                });
                event.currentTarget.classList.add('active');
            },
            toggle_app_only: function(event) {
                event.currentTarget.classList.toggle('active');
                this.app_only = !this.app_only;
            }
        }
    }
</script>