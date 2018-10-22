const path = require('path');
const gulp = require('gulp');
const flatten = require('gulp-flatten');
const rename = require('gulp-rename');
const size = require('gulp-size');
const svgstore = require('gulp-svgstore');
const svgmin = require('gulp-svgmin');

function optimizeIcons() {
    return gulp
        .src([
            path.join(process.env.XRD_SRC_ICONS, '**/*.svg'),
            path.join('!' + process.env.XRD_SRC_ICONS, '**/symbol-defs.svg'),
        ])
        .pipe(
            svgmin({
                plugins: [
                    {removeViewBox: false},
                    {removeUselessStrokeAndFill: true},
                    {removeAttrs: {attrs: ['xmlns', 'fill', 'stroke']}}
                ]
            })
        )
        .pipe(flatten())
        .pipe(gulp.dest(process.env.XRD_BUILD_ICONS));
}

function iconSprite() {
    return gulp
        .src(path.join(process.env.XRD_BUILD_ICONS, '**/*.svg'))
        .pipe(rename({prefix: 'icon-'}))
        .pipe(
            svgmin({
                plugins: [
                    {removeViewBox: false},
                    {removeUselessStrokeAndFill: true},
                    {removeAttrs: {attrs: ['xmlns', 'fill', 'stroke']}}
                ]
            })
        )
        .pipe(svgstore())
        .pipe(rename('icons.svg'))
        .pipe(size({
            title: 'Size Icon Sprite:',
            showFiles: true,
        }))
        .pipe(size({
            title: 'Size Icon Sprite:',
            showFiles: true,
            pretty: false,
            gzip: true
        }))
        .pipe(gulp.dest(process.env.XRD_PUBLIC_ICONS));
}

module.exports = {
    optimizeIcons,
    iconSprite
};
