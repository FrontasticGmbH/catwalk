const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin
const { merge } = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        {
            plugins: [
                // Anlyze bundle size
                new BundleAnalyzerPlugin({
                    reportFilename: 'bundleSize.html',
                    analyzerMode: 'static',
                }),
            ]
        },
        config,
    )
}
