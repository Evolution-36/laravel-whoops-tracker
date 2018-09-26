let status = {
    methods: {
        status_name: function(status) {
            return status === 0 ? 'Open' : (status === 1 ? 'Busy' : 'Closed');
        },
    }
};

export default status;