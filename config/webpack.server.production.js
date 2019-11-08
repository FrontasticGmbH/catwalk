const autoprefixer = require('autoprefixer')
const path = require('path')
const webpack = require('webpack')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const ManifestPlugin = require('webpack-manifest-plugin')
const InterpolateHtmlPlugin = require('react-dev-utils/InterpolateHtmlPlugin')
const SWPrecacheWebpackPlugin = require('sw-precache-webpack-plugin')
const paths = require('./paths')
const getClientEnvironment = require('./env')
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin
const StatsPlugin = require('stats-webpack-plugin')
const DuplicatePackageCheckerPlugin = require('duplicate-package-checker-webpack-plugin')

// Webpack uses `publicPath` to determine where the app is being served from.
// It requires a trailing slash, or the file assets will get an incorrect path.
const publicPath = paths.servedPath
// Some apps do not use client-side routing with pushState.
// For these, 'homepage' can be set to '.' to enable relative asset paths.
const shouldUseRelativeAssetPaths = publicPath === './'
// `publicUrl` is just like `publicPath`, but we will provide it to our app
// as %PUBLIC_URL% in `index.html` and `process.env.PUBLIC_URL` in JavaScript.
// Omit trailing slash as %PUBLIC_URL%/xyz looks better than %PUBLIC_URL%xyz.
const publicUrl = publicPath.slice(0, -1)
// Get environment variables to inject into our app.
const env = getClientEnvironment(publicUrl)
const ie11packages = require('./ie11packages')

const PRODUCTION = true
const SERVER = true

const mainConfig = require('./webpack.browser.production.js')

module.exports = {
    ...mainConfig,
    name: 'server',
    mode: PRODUCTION ? 'production' : 'development',
    target: 'node',
    entry: [
        // We ship a few polyfills by default:
        require.resolve('./polyfills'),
        // Finally, this is your app's code:
        paths.serverIndexJs,
        // We include the app code last so that if there is a runtime error during
        // initialization, it doesn't blow up the WebpackDevServer client, and
        // changing JS code would still trigger a refresh.
    ],
    output: {
        ...mainConfig.output,
        filename: 'assets/js/server.js',
    },
    plugins: [
        // Makes some environment variables available to the JS code, for example:
        // if (process.env.NODE_ENV === 'production') { ... }. See `./env.js`.
        // It is absolutely essential that NODE_ENV was set to production here.
        // Otherwise React will be compiled in the very slow development mode.
        new webpack.DefinePlugin({
            PRODUCTION: JSON.stringify(PRODUCTION),
            'process.env.NODE_ENV': '"production"',
        }),

        new webpack.IgnorePlugin(/^\.css$/, /\.scss$/),

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

        // Moment.js is an extremely popular library that bundles large locale files
        // by default due to how Webpack interprets its code. This is a practical
        // solution that requires the user to opt into importing specific locales.
        // https://github.com/jmblog/how-to-optimize-momentjs-with-webpack
        // You can remove this if you don't use Moment.js:
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),

        new webpack.optimize.LimitChunkCountPlugin({
            maxChunks: 1,
        }),
    ],
    module: {
        strictExportPresence: true,
        rules: [
            // ** ADDING/UPDATING LOADERS **
            // The 'file' loader handles all assets unless explicitly excluded.
            // The `exclude` list *must* be updated with every change to loader extensions.
            // When adding a new loader, you must add its `test`
            // as a new entry in the `exclude` list in the 'file' loader.

            // 'file' loader makes sure those assets end up in the `build` folder.
            // When you `import` an asset, you get its filename.
            {
                exclude: [
                    /\.html$/,
                    /\.(js|jsx)$/,
                    /\.css$/,
                    /\.scss$/,
                    /\.json$/,
                    /\.bmp$/,
                    /\.gif$/,
                    /\.jpe?g$/,
                    /\.png$/,
                ],
                loader: require.resolve('file-loader'),
                options: {
                    name: 'assets/media/[name].[hash:8].[ext]',
                },
            },
            // 'url' loader works just like 'file' loader but it also embeds
            // assets smaller than specified size as data URLs to avoid requests.
            {
                test: [/\.bmp$/, /\.gif$/, /\.jpe?g$/, /\.png$/],
                loader: require.resolve('url-loader'),
                options: {
                    limit: 10 * 1024,
                    name: 'assets/media/[name].[hash:8].[ext]',
                },
            },
            // Process JS with Babel.
            {
                test: /\.(js|jsx)$/,
                // exclude: /node_modules/,
                //
                // HACK - primarily for IE11.
                // Some packages we use don't come transpiled for older JS versions
                // and IE11 would complain. So we're blacklisting all node_modules
                // except those in the list (domino is a special case that will cause
                // more problems in strict mode if we transpile the whole thing).
                // Unfortunately, this list is not very scalable right now and needs to
                // be upgraded every time a package gets added that crashes IE11.
                // Also, we're using a negated exclude list, so we don't have to
                // specify all frontastic related packages, even though webpack recommends
                // an include based whitelist. *Marcel
                exclude: ie11packages,
                // On windows it can happen that the frontastic packages are
                // not linked but copied. In this case babel should still
                // compile the files in those folders.
                // exclude: webpackExcludes(['frontastic-catwalk', 'frontastic-common']),
                loader: require.resolve('babel-loader'),
                options: {
                    // This is a feature of `babel-loader` for webpack (not Babel itself).
                    // It enables caching results in ./node_modules/.cache/babel-loader/
                    // directory for faster rebuilds.
                    cacheDirectory: true,
                    // uses the babel.config.js in the project root
                    rootMode: 'upward',
                },
            },
            // ** STOP ** Are you adding a new loader?
            // Remember to add the new extension(s) to the 'file' loader exclusion list.
        ],
    },
}
