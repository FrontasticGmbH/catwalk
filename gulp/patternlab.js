const configPath = require('../config/patternlab-config.json');
const patternlabFactory = require("@pattern-lab/core")
const patternlab = patternlabFactory(configPath)

async function buildPatterns() {
    await patternlab.build({cleanPublic: false})
}

async function serve() {
    await patternlab.serve({cleanPublic: false})
}

module.exports = {
    buildPatterns,
    serve
};
