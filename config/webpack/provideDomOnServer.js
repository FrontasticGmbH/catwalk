const webpack = require('webpack')
const merge = require('webpack-merge')

module.exports = (config) => {
    return merge.smart(
        {
            plugins: [
                new webpack.ProvidePlugin({
                    'document': 'min-document',
                    'Element.prototype': 'node-noop',
                    'hostname': 'node-noop',
                    'location': 'node-noop',
                    'navigator.userAgent': 'empty-string',
                    'navigator.userAgent': 'empty-string',
                    'self.navigator.userAgent': 'empty-string',
                    'self': 'node-noop',
                    'window.Element.prototype': 'empty-string',
                    'window.location.href': 'empty-string',
                    'window.location': 'node-noop',
                    'window.navigation.userAgent': 'empty-string',
                    'window.navigator.userAgent': 'empty-string',
                }),
            ]
        },
        config,
    )
}
