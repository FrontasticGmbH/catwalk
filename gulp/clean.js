const del = require('del');

async function cleanBuild() {
    return await del([process.env.XRD_BUILD_BASE])
}

async function cleanPublic() {
    return await del([process.env.XRD_PUBLIC_BASE])
}

module.exports = {
    cleanBuild,
    cleanPublic
};
