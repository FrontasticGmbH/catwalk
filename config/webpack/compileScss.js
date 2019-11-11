const merge = require('webpack-merge')
const autoprefixer = require('autoprefixer')

module.exports = (config) => {
    return merge.smart(
        {
            module: {
                rules: [
                    {
                        test: /\.css$/,
                        use: [
                            require.resolve('style-loader'),
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
                                // Creates style nodes from JS strings
                                loader: 'style-loader',
                            },
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
        config,
    )
}
