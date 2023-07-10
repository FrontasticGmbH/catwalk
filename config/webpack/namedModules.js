const { merge } = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        {
            optimization: {
                moduleIds: 'named',
            }
        },
        config,
    )
}
