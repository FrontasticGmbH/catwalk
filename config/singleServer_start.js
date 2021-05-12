const fs = require('fs');
const { spawnSync } = require('child_process');
const process = require('process');

const path = 'build/assets/js/devSingleServer.js'
try {
    fs.writeFileSync(path, '', { flag: 'wx' }); // throws error if file exists
    console.log("touched " + path);
} catch (err) {
    // ignore, we only want the file to exist
}

// is required to avoid ENOENT error on windows, see
// https://stackoverflow.com/a/54515183/390808
const shell = process.platform == 'win32'

console.log('starting server')
console.log('sync!')

spawnSync(
    'nodemon',
    ['-e', 'js', '--watch', path, '--legacy-watch', path, path],
    {
        stdio: 'inherit',
        shell: shell,
        detached: false
    }
);
