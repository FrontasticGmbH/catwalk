const merge = require('webpack-merge')
const autoprefixer = require('autoprefixer')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const paths = require('../paths')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(config, {
        module: {
            /**
             *  This loader takes care of both CSS and SCSS, depending on the file.
             *
             *  The only difference between loading CSS and SCSS is,
             *  that the latter needs the sass loader, everything
             *  else stays the same. If the loader comes across a regular
             *  CSS file, the sass loader processes this as well, since
             *  CSS is a subset of SCSS, but it is pretty much a no-op.
             *
             *  The first loader is for CSS modules and is only used for
             *  files named like `my.module.css`
             *
             *  The second loader is for all other general SCSS processing.
             *
             **/
            rules: [
                {
                    test: /\.module\.s?css$/,
                    use: [
                        PRODUCTION ? MiniCssExtractPlugin.loader : require.resolve('style-loader'),
                        {
                            // Translates CSS into CommonJS
                            loader: require.resolve('css-loader'),
                            options: { modules: true, importLoaders: 1 },
                        },
                        {
                            loader: require.resolve('postcss-loader'),
                            options: {
                                ident: 'postcss',
                                plugins: () => {
                                    return [
                                        require('postcss-flexbugs-fixes'),
                                        autoprefixer(),
                                    ]
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
                {
                    test: /(?<!\.module)\.s?css$/,
                    use: [
                        PRODUCTION ? MiniCssExtractPlugin.loader : require.resolve('style-loader'),
                        {
                            // Translates CSS into CommonJS
                            loader: require.resolve('css-loader'),
                        },
                        {
                            loader: require.resolve('postcss-loader'),
                            options: {
                                ident: 'postcss',
                                plugins: () => {
                                    return [
                                        require('postcss-flexbugs-fixes'),
                                        autoprefixer(),
                                    ]
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
