const ManifestPlugin = require('webpack-manifest-plugin')
const merge = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge.smart(
        {
            plugins: [
                // Generate a manifest file which contains a mapping of all asset filenames
                // to their corresponding output file so that tools can pick it up without
                // having to parse `index.html`.
                new ManifestPlugin({
                    fileName: 'asset-manifest.json',
                }),
            ]
        },
        config,
    )
}
