<template>
    <div id="shower">
        <div class="whoops" v-if="whoops">
            <div class="row">
                <div class="col8">
                    <h5>{{ whoops.exception_class }}</h5>
                    <p class="error-long" v-on:click="open_error($event)">{{ whoops.message }}</p>
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
                <div>
                    <label for="status-filter">Occurrence</label>
                    <select v-model="selected_occurrence_id" @change="show_occurrence"
                            id="status-filter">
                        <option v-for="occurrence in whoops.lwt_occurrences" :value="occurrence.id">{{
                            occurrence.occurred_at }}
                        </option>
                    </select>
                </div>
            </div>

            <div v-if="occurrence">
                <div class="tabs row">
                    <button class="tab active" id="tab-default"
                            v-on:click="open_tab($event, 'trace-tab')">
                        Stack trace
                    </button>
                    <button class="tab" v-on:click="open_tab($event, 'request-tab')">
                        Request info
                    </button>
                    <button class="tab" v-on:click="open_tab($event, 'env-tab')">
                        Environment settings
                    </button>
                    <button class="tab" v-on:click="open_tab($event, 'server-tab')">
                        Server data
                    </button>
                </div>

                <div class="tab-content active" id="trace-tab">
                    <h4>Stack trace</h4>
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
                <div class="tab-content" id="request-tab">
                    <h4>GET parameters</h4>
                    <table v-if="!Array.isArray(occurrence.log.request.query)">
                        <tr>
                            <th>Parameter</th>
                            <th>Value</th>
                        </tr>
                        <tr v-for="(value, parameter) in occurrence.log.request.query">
                            <td>{{ parameter }}</td>
                            <td>{{ value }}</td>
                        </tr>
                    </table>
                    <p v-else>
                        No GET parameters
                    </p>

                    <h4>POST parameters</h4>
                    <table v-if="!Array.isArray(occurrence.log.request.request)">
                        <tr>
                            <th>Parameter</th>
                            <th>Value</th>
                        </tr>
                        <tr v-for="(value, parameter) in occurrence.log.request.request">
                            <td>{{ parameter }}</td>
                            <td>{{ value }}</td>
                        </tr>
                    </table>
                    <p v-else>
                        No POST parameters
                    </p>

                    <h4>Cookies</h4>
                    <table v-if="!Array.isArray(occurrence.log.request.cookies)">
                        <tr>
                            <th>Cookie</th>
                            <th>Value</th>
                        </tr>
                        <tr v-for="(value, cookie) in occurrence.log.request.cookies">
                            <td>{{ cookie }}</td>
                            <td>{{ value }}</td>
                        </tr>
                    </table>

                    <p v-else>
                        No cookies
                    </p>

                    <h4>Request headers</h4>
                    <table v-if="!Array.isArray(occurrence.log.request.headers)">
                        <tr>
                            <th>Header</th>
                            <th>Value</th>
                        </tr>
                        <tr v-for="(value, header) in occurrence.log.request.headers">
                            <td>{{ header }}</td>
                            <td>{{ value[0] }}</td>
                        </tr>
                    </table>

                    <p v-else>
                        No headers
                    </p>
                </div>
                <div class="tab-content" id="server-tab">
                    <h4>Server data</h4>
                    <table v-if="!Array.isArray(occurrence.log.request.server)">
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                        </tr>
                        <tr v-for="(value, name) in occurrence.log.request.server">
                            <td>{{ name }}</td>
                            <td>{{ value }}</td>
                        </tr>
                    </table>
                    <p v-else>
                        No server settings
                    </p>
                </div>
                <div class="tab-content" id="env-tab">
                    <h4>Environment settings</h4>
                    <table v-if="!Array.isArray(occurrence.log.environment)">
                        <tr>
                            <th>Setting</th>
                            <th>Value</th>
                        </tr>
                        <tr v-for="(value, setting) in occurrence.log.environment">
                            <td>{{ setting }}</td>
                            <td>{{ value }}</td>
                        </tr>
                    </table>
                    <p v-else>
                        No server settings
                    </p>
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
                    console.log(response.data.log.request.files);
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
            },
            open_error: function (event) {
                event.target.classList.toggle("active");
            }
        }
    }
</script>