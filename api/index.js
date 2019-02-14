const bodyParser = require('body-parser');
const express    = require('express');
const cors       = require('cors');
const docker     = require('dockerode');
const utils      = require('./utils');

const request = docker({
    host: '192.168.0.9:5555'
})

var dockerClient = new docker({host: '127.0.0.1', port: 5555});

const app = express();

app.use(cors());
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const port = process.env.port || 3000;
app.listen(port, () =>
    console.log(`App listening on port ${port}!`)
)

app.get('/servers/', (req, res) => {
    dockerClient.listContainers({all: true}, function (err, containers) {
        if (err) {
            return res.status(400).json(err.reason);
        }

        var data = [];
        containers.forEach(function (containerInfo) {
            data.push(utils.formatContainer(containerInfo));
        });

        return res.status(200).json(data);
    });
});

app.get('/servers/:id/start', (req, res) => {
    dockerClient.getContainer(req.params.id).start(function(err, data) {
        if (err) {
            return res.status(400).json(err.reason);
        }

        return res.status(200).json();
    });
});

app.get('/servers/:id/stop', (req, res) => {
    dockerClient.listContainers({all: true, filters: {id: [req.params.id]}}, function (err, container) {
        if (container[0].Names.indexOf('/seedbox_db') != -1 || container[0].Names.indexOf('/seedbox_frontend_web') != -1) {
            return res.status(400).json('Ce serveur ne peut être arrêté');
        }

        dockerClient.getContainer(req.params.id).stop(function(err, data) {
            if (err) {
                return res.status(400).json(err.reason);
            }

            return res.status(200).json();
        });
    });
});

app.delete('/servers/:id/delete', (req, res) => {
    dockerClient.getContainer(req.params.id).remove(function(err, data) {
        if (err) {
            return res.status(400).json(err.json.message);
        }

        return res.status(200).json();
    });
});

app.post('/servers/create', (req, res) => {
    if (typeof req.body.name === 'undefined' || req.body.name == '') {
        return res.status(400).json('Bad request');
    }

    dockerClient.createContainer({Image: 'frontend_web', name: req.body.name}, function (err, container) {
        if (err) {
            return res.status(400).json(err.reason);
        }

        return res.status(200).json();
    });
});
