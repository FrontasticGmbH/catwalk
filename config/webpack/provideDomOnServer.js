const webpack = require('webpack')
const merge = require('webpack-merge')

module.exports = (config) => {
    return merge.smart(
        {
            plugins: [
                new webpack.ProvidePlugin({
                    'hostname': 'node-noop',
                    'location': 'node-noop',
                    'navigator.userAgent': 'empty-string',
                    'self.navigator.userAgent': 'empty-string',
                    'self': 'node-noop',
                }),
            ]
        },
        config,
    )
}
