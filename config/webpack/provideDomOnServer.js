const webpack = require('webpack')
const { merge } = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        {
            plugins: [
                new webpack.ProvidePlugin({
                    'hostname': 'node-noop',
                    'location': 'node-noop',
                    'navigator.userAgent': 'empty-string',
                    'self.navigator.userAgent': 'empty-string',
                    'self': 'node-noop',
                    'document.styleSheets': 'node-noop',
                }),
            ]
        },
        config,
    )
}
