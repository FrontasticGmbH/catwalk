const autoprefixer = require('autoprefixer')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const merge = require('webpack-merge')

// @TODO Re-use and extend the definitions from compileScss.js
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
                                loader: MiniCssExtractPlugin.loader,	
                            },	
                            {	
                                loader: require.resolve('css-loader'), // translates CSS into CommonJS	
                            },	
                            {	
                                loader: require.resolve('postcss-loader'),	
                                options: {	
                                    // Necessary for external CSS imports to work	
                                    // https://github.com/facebookincubator/create-react-app/issues/2677	
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
                                loader: MiniCssExtractPlugin.loader,	
                            },	
                            {	
                                loader: require.resolve('css-loader'), // translates CSS into CommonJS	
                            },	
                            {	
                                loader: require.resolve('postcss-loader'),	
                                options: {	
                                    // Necessary for external CSS imports to work	
                                    // https://github.com/facebookincubator/create-react-app/issues/2677	
                                    ident: 'postcss',	
                                    plugins: () => {	
                                        return [require('postcss-flexbugs-fixes'), autoprefixer()]	
                                    },	
                                },	
                            },	
                            {	
                                loader: require.resolve('resolve-url-loader'), // Resolve relative url() paths	
                            },
                            {	
                                loader: require.resolve('sass-loader'), // compiles Sass to CSS	
                                options: {	
                                    sourceMap: true, // resolve-url-loader requires a sourceMap, will be skipped afterwards	
                                },	
                            },	
                        ],	
                    },
                ],
            },
            plugins: [
                // Extract CSS code statically
                new MiniCssExtractPlugin({
                    // Options similar to the same options in
                    // webpackOptions.output both options are optional
                    filename: 'assets/css/[name].[hash:8].css',
                    chunkFilename: 'assets/css/[name].[hash:8].css',
                }),
            ],
        },
    )
}
