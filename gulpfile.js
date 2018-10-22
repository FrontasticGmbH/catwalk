const gulp = require('gulp');
// const {copyAssets, copyImages} = require('./gulp/copy');
const env = require('./gulp/env');
const rollup = require('./gulp/rollup');
const {buildPatterns, serve} = require('./gulp/patternlab');
const {scss, css} = require('./gulp/styles');
const {optimizeIcons, iconSprite} = require('./gulp/icons');
const optimizeImages = require('./gulp/images');
const watch = require('./gulp/watch');
const {cleanBuild, cleanPublic} = require('./gulp/clean');

gulp.task('set-env', env);
gulp.task('js', rollup);
gulp.task('patternlab', buildPatterns);
gulp.task('scss', scss);
gulp.task('css', css);
gulp.task('preview', serve);
gulp.task('optimizeIcons', optimizeIcons);
gulp.task('iconSprite', iconSprite);
gulp.task('images', optimizeImages);
// gulp.task('assets', copyAssets);
gulp.task('watch', watch);
gulp.task('cleanPublic', cleanPublic);
gulp.task('cleanBuild', cleanBuild);

/**
 * High-level deployment tasks
 */
// gulp.task('default', gulp.series(gulp.parallel('js', gulp.series('iconSprite', 'css'), 'patternlab', 'images'), 'assets'));

gulp.task('default',
    gulp.series(
        'set-env',
        gulp.parallel(
            'cleanBuild',
            'cleanPublic'
        ),
        gulp.parallel(
            'js',
            'images',
            gulp.series(
                'optimizeIcons',
                'scss',
                'css',
                'iconSprite',
            ),
            'patternlab'
        )
    )
);



gulp.task('develop',
    gulp.series(
        'default',
        gulp.parallel(
            'watch',
            'preview'
        )
    )
);
