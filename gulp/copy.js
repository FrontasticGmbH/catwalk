const path = require('path');
const gulp = require('gulp');

async function copyImages() {
    return await gulp.src(path.join(process.env.XRD_PRIVATE_IMAGES, '**/*'))
        .pipe(gulp.dest(process.env.XRD_PUBLIC_IMAGES));
}

async function copyAssets() {
    const patternAssets = await gulp.src([
        path.join(process.env.XRD_PUBLIC, 'Application/**/*'),
        path.join(process.env.XRD_PUBLIC, 'Application/!**/Patterns/**')
    ])
        .pipe(gulp.dest(process.env.XRD_PUBLIC_PATTERN_ASSETS));

    const data = await gulp.src(path.join(process.env.XRD_PRIVATE, 'Application/Data/*.json'))
        .pipe(gulp.dest(path.join(process.env.XRD_PUBLIC_PATTERN_ASSETS, 'Data')));
    return Promise.all[patternAssets, data];
}

module.exports = {
    copyImages,
    copyAssets
};
