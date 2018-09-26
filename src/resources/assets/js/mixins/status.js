let status = {
    data () {
        return {
            status_filter_options: [
                {name: 'Open', value: 0},
                {name: 'Busy', value: 1},
                {name: 'Closed', value: 2},
            ]
        }
    },
    methods: {
        status_name: function(status) {
            return (this.status_filter_options.find(
                    status_filter => (
                        status_filter.value === status)
                    ) || {name: 'Closed'}
                ).name
        },
    }
};

export default status;