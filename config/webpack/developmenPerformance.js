const merge = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge.smart(
        {
            // Turn off performance hints during development because we don't do
            // any splitting or minification in interest of speed. These warnings
            // become cumbersome.
            performance: {
                hints: false,
            },
        },
        config,
    )
}
