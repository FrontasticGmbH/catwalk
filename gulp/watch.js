const gulp = require('gulp');
const path = require('path');

function watch() {
    const watchConf = [{
        src: path.join(process.env.XRD_SRC_SCSS, '**/*.scss'),
        tasks: [
            gulp.series(
                'scss',
                'css'
            )
        ]
    },
    {
        src: path.join(process.env.XRD_SRC_ICONS, '**/*.svg'),
        tasks: [
            gulp.series(
                'optimizeIcons',
                'iconSprite',
                'css'
            )
        ]
    },
    {
        src: path.join(process.env.XRD_SRC_JS, '**/*.js'),
        tasks: ['js']
    },
    {
        src: [path.join(process.env.XRD_SRC_IMG, '**/*.{png,jpg,jpeg,webp,gif,svg}')],
        tasks: ['images']
    }
    // {
    //     src: path.join(process.env.XRD_SRC_SVG, '**/*.svg'),
    //     tasks: ['svg']
    // }, {
    //     src: path.join(process.env.XRD_SRC_PATTERNS, '**/*'),
    //     tasks: ['patternlab']
    // }
    ];
    watchConf.map(c => gulp.watch(c.src, gulp.parallel(c.tasks)));
}

module.exports = watch;
