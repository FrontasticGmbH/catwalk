// NODE_ENV=development ../node_modules/.bin/webpack-cli --watch --config ../node_modules/@frontastic/catwalk/config/webpack.server.development.js
const { spawn, exec } = require('child_process');
const process = require('process');

// is required to avoid ENOENT error on windows, see
// https://stackoverflow.com/a/54515183/390808
const shell = process.platform == 'win32'

console.log('starting ssr compile')
process.env.NODE_ENV = 'development'
spawn(
    'webpack-cli',
    ['--watch', '--config', '../node_modules/@frontastic/catwalk/config/webpack.server.development.js'],
    {stdio: 'inherit', shell: shell, env: process.env}
);
