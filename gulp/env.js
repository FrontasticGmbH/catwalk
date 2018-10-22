const env = require('gulp-env');
const dotenv = require('dotenv');
const path = require('path');
const pkg = require('../package.json');
let envResult = {}

// Only configure local evns via dot file
if (!process.env.NODE_ENV || process.env.NODE_ENV === 'development') {
    // TODO: Remove nasty side-effect
    const {parsed} = dotenv.config();
    envResult = Object.assign({}, parsed)
}

const ENV_PATH_PREFIX = 'XRD';
const head = xs => xs[0];
const flatten = xs => xs.reduce(
    (a, b) => a.concat(Array.isArray(b) ? flatten(b) : b), []
);
const reducePaths = (o, prefix) => Object
    .keys(o)
    .map(dir => typeof o[dir] === 'object'
        ? reducePaths(o[dir], `${prefix}_${dir.toUpperCase()}`)
        : ({[`${prefix}_${dir.toUpperCase()}`]: o[dir]}))

const paths = flatten(reducePaths(pkg.paths, ENV_PATH_PREFIX)).reduce((acc, o) => ({...o, ...acc}))

const setEnv = async () => await env({
    vars: Object.assign(paths, envResult)
});

module.exports = setEnv;
