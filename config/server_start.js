// touch build/assets/js/devServer.js ;
const fs = require('fs');
const path = 'build/assets/js/devServer.js'
try {
    fs.writeFileSync(path, '', { flag: 'wx' }); // throws error if file exists
    console.log("touched "+path);
} catch(err) {
    // ignore, we only want the file to exists
}

// NODE_ICU_DATA=$(node-full-icu-path)
// nodemon -e js --watch build/assets/js/devServer.js --legacy-watch build/assets/js/devServer.js build/assets/js/devServer.js
const { spawn, exec } = require('child_process');
const process = require('process');

/*
  node-full-icu-path seems not even installed (package full-icu)
exec('node-full-icu-path', function(error, stdout, stderr) {
    console.log(error)
    console.log(stdout)
    process.env.NODE_ICU_DATA = stdout
    spawn('nodemon', ['-e', 'js', '--watch', path, '--legacy-watch', path, path], {env: process.env});
});
*/

console.log('starting server')

// is required to avoid ENOENT error on windows, see
// https://stackoverflow.com/a/54515183/390808
const shell = process.platform == 'win32'

spawn(
    'nodemon',
    ['-e', 'js', '--watch', path, '--legacy-watch', path, path],
    {stdio: 'inherit', shell: shell}
);
