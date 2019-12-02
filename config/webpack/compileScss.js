const path = require('path')
const merge = require('webpack-merge')
const autoprefixer = require('autoprefixer')
const paths = require('./../paths')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(config, {
        module: {
            rules: [
                /**
                 *  This loader takes care of both CSS and SCSS, depending on the file.
                 *
                 *  The only difference between loading CSS and SCSS is,
                 *  that the latter needs the sass loader, everything
                 *  else stays the same. If the loader comes across a regular
                 *  CSS file, the sass loader processes this as well, since
                 *  CSS is a subset of SCSS, but it is pretty much a no-op.
                 *
                 *  There sre currently two different loaders, which are chained
                 *  through `oneOf`, which means that the first loader that matches
                 *  will be used.
                 *
                 *  The first loader is for CSS modules and is only used for
                 *  all files underneath the JS folder, because the assumption
                 *  is made, that all CSS/SCSS files in there, will be CSS Modules.
                 *
                 *  The second loader is for all other general SCSS processing.
                 *
                 **/
                {
                    test: /\.module\.s?css$/,
                    use: [
                        PRODUCTION ? MiniCssExtractPlugin.loader : require.resolve('style-loader'),
                        {
                            loader: 'css-loader',
                            options: { modules: true, importLoaders: 1 },
                        },
                        {
                            loader: require.resolve('postcss-loader'),
                            options: {
                                ident: 'postcss',
                                plugins: () => {
                                    return [require('postcss-flexbugs-fixes'), autoprefixer()]
                                },
                            },
                        },
                        {
                            // Resolve relative url() paths
                            loader: require.resolve('resolve-url-loader'),
                        },
                        {
                            // Compiles Sass to CSS
                            loader: require.resolve('sass-loader'),
                            options: {
                                sassOptions: (loaderContext) => {
                                    return { includePaths: ['../paas/themes/frontastic/boost/src/scss'] }
                                },
                            },
                        },
                    ],
                },
                {
                    test: /\.s?css$/,
                    use: [
                        PRODUCTION ? MiniCssExtractPlugin.loader : require.resolve('style-loader'),
                        {
                            loader: 'css-loader',
                            options: { modules: true, importLoaders: 1 },
                        },
                        {
                            loader: require.resolve('postcss-loader'),
                            options: {
                                ident: 'postcss',
                                plugins: () => {
                                    return [require('postcss-flexbugs-fixes'), autoprefixer()]
                                },
                            },
                        },
                        {
                            // Resolve relative url() paths
                            loader: require.resolve('resolve-url-loader'),
                        },
                        {
                            // Compiles Sass to CSS
                            loader: require.resolve('sass-loader'),
                            options: {
                                sourceMap: true,
                            },
                        },
                    ],
                },
            ],
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'assets/css/[name].[hash:8].css',
                chunkFilename: 'assets/css/[name].[hash:8].css',
            }),
        ],
    })
}
