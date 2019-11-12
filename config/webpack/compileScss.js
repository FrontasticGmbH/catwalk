const merge = require('webpack-merge')
const autoprefixer = require('autoprefixer')

module.exports = (config) => {
    return merge.smart(
        config,
        {
            module: {
                rules: [
                    {
                        test: /\.css$/,
                        use: [
                            {
                                loader: require.resolve('css-loader'),
                                options: {
                                    importLoaders: 1,
                                },
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
                        ],
                    },
                    {
                        test: /\.scss$/,
                        use: [
                            {
                                // Translates CSS into CommonJS
                                loader: require.resolve('css-loader'),
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
        },
    )
}
