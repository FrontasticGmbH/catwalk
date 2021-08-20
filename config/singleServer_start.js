const fs = require('fs');
const { spawnSync } = require('child_process');
const process = require('process');

const folder = 'build/assets/js/'
const path = folder + 'devSingleServer.js'
try {
    fs.writeFileSync(path, '', { flag: 'wx' }); // throws error if file exists
    if (!fs.existsSync(path)) {
        if (!fs.existsSync(folder)) {
            fs.mkdirSync(folder, { recursive: true })
        }
        fs.writeFileSync(path, '')
    }
} catch (err) {
    // ignore, we only want the file to exist
}

// is required to avoid ENOENT error on windows, see
// https://stackoverflow.com/a/54515183/390808
const shell = process.platform == 'win32'

var nodemonCheck = spawnSync('nodemon',
    ['-v'],
    {
        stdio: 'pipe',
        shell: shell,
        detached: false,
        encoding: 'utf-8'
    })

if (nodemonCheck.stderr) {
    var err = `Cannot start ${path}, ${nodemonCheck.stderr}`
    if (nodemonCheck.stderr.indexOf(`'nodemon' is not recognized as an internal or external command`) > -1
        || nodemonCheck.stderr.indexOf('command not found') > -1) {
        err += `'nodemon' must be installed globally. Run 'npm install nodemon -g' and try again.`
    }
    throw new Error(err)
} else {
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
}
