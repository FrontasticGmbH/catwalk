const { GenerateSW } = require('workbox-webpack-plugin')
const CopyPlugin = require('copy-webpack-plugin')
const { merge } = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        {
            plugins: [
                new CopyPlugin({
                    patterns: ['public/assets/*.png', 'public/assets/*.svg'],
                }),
                new GenerateSW({
                    // By default, a cache-busting query parameter is appended to requests
                    // used to populate the caches, to ensure the responses are fresh.
                    // If a URL is already hashed by Webpack, then there is no concern
                    // about it being stale, and the cache-busting can be skipped.
                    dontCacheBustURLsMatching: /\.\w{8}\./,
                    modifyURLPrefix: {
                        '/public/': '/',
                    },
                    // For unknown URLs, fallback to the index page
                    navigateFallback: '/',
                    // Ignores URLs starting from /__ (useful for Firebase):
                    // https://github.com/facebookincubator/create-react-app/issues/2237#issuecomment-302693219
                    navigateFallbackAllowlist: [/^(?!\/__).*/],
                    // Don't precache sourcemaps (they're large) and build asset manifest:
                    exclude: [/\.map$/, /asset-manifest\.json$/, /LICENSE/],
                    clientsClaim: true,
                    skipWaiting: true,
                })
            ]
        },
        config,
    )
}
