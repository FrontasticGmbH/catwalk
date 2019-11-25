const webpack = require('webpack')
const merge = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge.smart(
        {
            plugins: [
                // Add module names to factory functions so they appear in browser
                // profiler.
                new webpack.NamedModulesPlugin(),
            ]
        },
        config,
    )
}
