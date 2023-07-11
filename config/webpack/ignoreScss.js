const webpack = require('webpack')
const { merge } = require('webpack-merge')
const autoprefixer = require('autoprefixer')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        config,
        {
            plugins: [
                new webpack.IgnorePlugin({
                    resourceRegExp: /^\.css$/,
                    contextRegExp: /\.scss$/,
                }),
                new MiniCssExtractPlugin({
                    filename: '../build/css/[name].[contenthash:8].css',
                    chunkFilename: '../build/css/[name].[contenthash:8].css',
                }),
            ],
            module: {
                rules: [
                    {
                        test: /\.module\.s?css$/,
                        use: [
                            MiniCssExtractPlugin.loader,
                            {
                                // Translates CSS into CommonJS
                                loader: require.resolve('css-loader'),
                                options: { modules: true, importLoaders: 1 },
                            },
                            {
                                loader: require.resolve('postcss-loader'),
                                options: {
                                    postcssOptions: {
                                        plugins: [
                                            require('postcss-flexbugs-fixes'),
                                            autoprefixer(),
                                        ],
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
                        test: [
                            /(?<!\.module)\.css$/,
                            /(?<!\.module)\.scss$/,
                            /\.less/,
                        ],
                        use: [
                            {loader: 'ignore-loader'},
                        ],
                    },
                ],
            },
        },
    )
}
