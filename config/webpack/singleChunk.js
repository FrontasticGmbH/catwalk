const webpack = require('webpack')
const merge = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge.smart(
        {
            plugins: [
                new webpack.optimize.LimitChunkCountPlugin({
                    maxChunks: 1,
                }),
            ]
        },
        config,
    )
}
