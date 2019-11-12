const webpack = require('webpack')
const merge = require('webpack-merge')

module.exports = (config) => {
    return merge.smart(
        config,
        {
            plugins: [
                new webpack.IgnorePlugin(/^\.css$/, /\.scss$/),
            ]
        },
    )
}
