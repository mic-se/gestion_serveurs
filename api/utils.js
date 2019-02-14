module.exports = {
    formatContainer: function(container) {
        var networks = container.NetworkSettings.Networks;
        var ips = [];
        for(var i in networks) {
            if (networks[i].IPAddress != '') {
                ips.push(networks[i].IPAddress);
            }
        };

        data = {
            id: container.Id,
            names: container.Names,
            created: container.Created,
            state: container.State,
            ips: ips
        };

        return data;
    }
}
