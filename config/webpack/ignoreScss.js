const webpack = require('webpack')
const merge = require('webpack-merge')

module.exports = (config) => {
    return merge.smart(
        config,
        {
            plugins: [
                new webpack.IgnorePlugin(/^\.css$/, /\.scss$/),
            ],
            module: {
                rules: [
                    {
                        test: [
                            /\.css$/,
                            /\.scss$/,
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
