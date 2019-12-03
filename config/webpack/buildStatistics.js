const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin
const StatsPlugin = require('stats-webpack-plugin')
const merge = require('webpack-merge')

module.exports = (config) => {
    return merge.smart(
        {
            plugins: [
                // Anlyze bundle size
                new BundleAnalyzerPlugin({
                    reportFilename: 'bundleSize.html',
                    analyzerMode: 'static',
                }),

                // Webpack dependency graph and other stats as JSON
                new StatsPlugin('bundleStats.json', {
                    chunkModules: true,
                    exclude: [/node_modules[\\\/]react/],
                }),
            ]
        },
        config,
    )
}
