const fs = require('fs');
const { spawnSync } = require('child_process');
const process = require('process');

// touch build/assets/js/devServer.js ;
const path = 'build/assets/js/devServer.js'
try {
    fs.writeFileSync(path, '', { flag: 'wx' }); // throws error if file exists
    console.log("touched "+path);
} catch(err) {
    // ignore, we only want the file to exist
}

// NODE_ICU_DATA=$(node-full-icu-path)
// nodemon -e js --watch build/assets/js/devServer.js --legacy-watch build/assets/js/devServer.js build/assets/js/devServer.js

/*
  node-full-icu-path seems not even installed (package full-icu)
exec('node-full-icu-path', function(error, stdout, stderr) {
    console.log(error)
    console.log(stdout)
    process.env.NODE_ICU_DATA = stdout
    spawn('nodemon', ['-e', 'js', '--watch', path, '--legacy-watch', path, path], {env: process.env});
});
*/

const onWindows = process.platform == 'win32'
// is required to avoid ENOENT error on windows, see
// https://stackoverflow.com/a/54515183/390808
const shell = onWindows

const ac = new AbortController();

function handleStop() {
    console.log('Received signal. Stopping child.');
    ac.abort();
    process.exit(1);
}

// TODO: windows seems to  make problems when listening to these events, only do on non-windows
if (!onWindows) {
    process.once('SIGINT', handleStop)
    process.once('SIGTERM', handleStop)
    process.once('SIGBREAK', handleStop)
}

console.log('starting server')
console.log('sync!')

spawnSync(
    'nodemon',
    ['-e', 'js', '--watch', path, '--legacy-watch', path, path],
    {
        stdio: 'inherit',
        shell: shell,
        detached: false,
        abort: ac
    }
);
