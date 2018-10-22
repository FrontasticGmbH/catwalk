const path = require('path');
const gulp = require('gulp');
const imagemin = require('gulp-imagemin');
const size = require('gulp-size');

async function optimizeImages() {
    return gulp
        .src(path.join(process.env.XRD_SRC_IMG, '**/*.{png,jpg,jpeg,webp,gif,svg}'))
        .pipe(imagemin({
            progressive: true,
            interlaced: true,
            optimizationLevel: 7,
            svgoPlugins: [{removeViewBox: false}],
            verbose: true,
            use: []
        }))
        .pipe(gulp.dest(process.env.XRD_PUBLIC_IMG));
}

module.exports = optimizeImages;
