const merge = require('webpack-merge')

// Using a normal merge instead of a smart merge,
// because the smart merge only decides based
// on `Rule.test` if a loader is unique.
// But here the only thing that's different is the Rule.issuer.
//
// If an SVG gets imported from a jsx file it will be
// available for import:
// - default import yields a base64 string
// - named export { ReactComponent } yields a React Component (duh!)
//
// If a SVG is referenced in all other files (scss for example),
// the regular file loader kicks in.
module.exports = (config, PRODUCTION, SERVER) => {
    return merge(config, {
        module: {
            rules: [
                {
                    test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
                    issuer: {
                        test: /\.jsx?$/,
                    },
                    use: [
                        {
                            loader: '@svgr/webpack',
                            options: {
                                icon: true,
                            },
                        },
                        'url-loader',
                    ],
                },
                {
                    test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
                    use: ['url-loader'],
                },
            ],
        },
    })
}
