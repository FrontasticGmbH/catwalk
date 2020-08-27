const merge = require('webpack-merge')
const LodashModuleReplacementPlugin = require('lodash-webpack-plugin')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge.smart(
        {
            resolve: {
                alias: {
                    lodash: 'lodash-es',
                },
            },
            plugins: [
                new LodashModuleReplacementPlugin({
                    // shorthands: true,
                })
            ],
        },
        config,
    )
}
