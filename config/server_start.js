const fs = require('fs');
const { spawnSync } = require('child_process');
const process = require('process');

// touch build/assets/js/devServer.js ;
const path = 'build/assets/js/devServer.js'
try {
    fs.writeFileSync(path, '', { flag: 'wx' }); // throws error if file exists
    console.log("touched " + path);
} catch (err) {
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

console.log('starting server')
console.log('sync!')

spawnSync(
    'nodemon',
    ['-e', 'js', '--watch', path, '--legacy-watch', path, path],
    {
        stdio: 'inherit',
        shell: onWindows,
        detached: false
    }
);
