const path = require('path');
const gulp = require('gulp');
const postcss = require('gulp-postcss');
const sass = require('gulp-sass');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const inlineSvg = require('postcss-inline-svg')
const flexbugsFixes = require('postcss-flexbugs-fixes')
const size = require('gulp-size');

const sassConf = {
    sourceMapEmbed: (process.env.NODE_ENV === 'production' ? false : true),
    noCache: !(process.env.NODE_ENV === 'production')
};

const cssnanoConf = {
    discardComments: {
        removeAll: true
    },
    discardDuplicates: true,
    discardEmpty: true,
    reduceIdents: false,
    minifyFontValues: true,
    minifySelectors: true,
    zindex: false
}

function scss() {
    return gulp.src(path.join(process.env.XRD_SRC_SCSS, '*.scss'))
        .pipe(sourcemaps.init())
        .pipe(sass(sassConf).on('error', sass.logError))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(process.env.XRD_BUILD_CSS));
}

async function css() {
    return gulp.src(path.join(process.env.XRD_BUILD_CSS, '*.css'))
        .pipe(sourcemaps.init())
        .pipe(size({
            title: 'Size CSS:',
            showFiles: true,
            showTotal: false
        }))
        .pipe(postcss([
            inlineSvg({
                path: process.env.XRD_BUILD_ICONS
            }),
            flexbugsFixes(),
            autoprefixer(),
            cssnano(cssnanoConf)
        ]))
        .pipe(rename({extname: ".min.css"}))
        .pipe(size({
            title: 'Size CSS minimized:',
            showFiles: true,
            showTotal: false
        }))
        .pipe(size({
            title: 'Size CSS minimized:',
            gzip: true,
            showFiles: true,
            showTotal: false
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(process.env.XRD_PUBLIC_CSS));
}


module.exports = {
    scss,
    css
};
