const webpack = require('webpack')
const paths = require('./paths')
const path = require('path')

const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin')
const DuplicatePackageCheckerPlugin = require('duplicate-package-checker-webpack-plugin')

// Webpack uses `publicPath` to determine where the app is being served from.
// In development, we always serve from the root. This makes config easier.
const publicPath = '/'
// `publicUrl` is just like `publicPath`, but we will provide it to our app
// as %PUBLIC_URL% in `index.html` and `process.env.PUBLIC_URL` in JavaScript.
// Omit trailing slash as %PUBLIC_PATH%/xyz looks better than %PUBLIC_PATH%xyz.
const publicUrl = ''

const ie11packages = require('./ie11packages')

module.exports = (PRODUCTION, SERVER) => {
    if (typeof PRODUCTION === 'undefined') {
        return console.error('Variable PRODUCTION must be defined.')
    }

    if (typeof SERVER === 'undefined') {
        return console.error('Variable SERVER must be defined.')
    }

    const assetBaseDir = PRODUCTION ? 'assets/' : 'webpack/'

    return {
        mode: PRODUCTION ? 'production' : 'development',
        // You may want 'eval' instead if you prefer to see the compiled output
        // in DevTools.  See the discussion in
        // https://github.com/facebookincubator/create-react-app/issues/343.
        devtool: PRODUCTION ? false : 'cheap-module-source-map',
        // These are the "entry points" to our application. This means they
        // will be the "root" imports that are included in JS bundle.
        entry: [
            // We ship a few polyfills by default:
            require.resolve('./polyfills'),
            // Finally, this is your app's code:
            SERVER ? paths.serverIndexJs : paths.appIndexJs,
            // We include the app code last so that if there is a runtime error
            // during initialization, it doesn't blow up the WebpackDevServer
            // client, and changing JS code would still trigger a refresh.
        ],
        output: {
            // Next line is not used in dev but WebpackDevServer crashes
            // without it:
            path: paths.appBuild,
            // Add /* filename */ comments to generated require()s in the output.
            pathinfo: true,
            filename: assetBaseDir + 'js/[name].[hash:8].js',
            chunkFilename: assetBaseDir + 'assets/js/[name].[hash:8].chunk.js',
            // This is the URL that app is served from. We use "/" in development.
            publicPath: publicPath,
            // Point sourcemap entries to original disk location (format as URL on Windows)
            devtoolModuleFilenameTemplate: (info) => {
                return path.resolve(info.absoluteResourcePath).replace(/\\/g, '/')
            },
        },
        resolve: {
            // This allows you to set a fallback for where Webpack should look
            // for modules.  We placed these paths second because we want
            // `node_modules` to "win" if there are any conflicts. This matches
            // Node resolution mechanism.
            modules: [
                paths.appNodeModules, // the node modules of the project
                path.resolve(__dirname, '../node_modules'), // node_modules of catwalk
                path.resolve(__dirname, '../../../node_modules'), // global node_modules
            ].concat(
                // It is guaranteed to exist because we tweak it in `env.js`
                process.env.NODE_PATH.split(path.delimiter).filter(Boolean)
            ),
            // These are the reasonable defaults supported by the Node
            // ecosystem.  We also include JSX as a common component filename
            // extension to support some tools, although we do not recommend
            // using it.
            extensions: ['.web.js', '.js', '.jsx', '.json', '.web.jsx'],
        },
        plugins: [
            // Add module names to factory functions so they appear in browser profiler.
            new webpack.NamedModulesPlugin(),
            // Makes some environment variables available to the JS code, for example:
            new webpack.DefinePlugin({
                PRODUCTION: JSON.stringify(PRODUCTION),
                'process.env.NODE_ENV': PRODUCTION ? '"production"' : '"development"',
            }),
            // Watcher doesn't work well if you mistype casing in a path so we use
            // a plugin that prints an error when you attempt to do this.
            // See https://github.com/facebookincubator/create-react-app/issues/240
            new CaseSensitivePathsPlugin(),
            // Moment.js is an extremely popular library that bundles large locale files
            // by default due to how Webpack interprets its code. This is a practical
            // solution that requires the user to opt into importing specific locales.
            // https://github.com/jmblog/how-to-optimize-momentjs-with-webpack
            // You can remove this if you don't use Moment.js:
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
            // Show packages which are included from multiple locations, which
            // increases the build size.
            new DuplicatePackageCheckerPlugin({
                // Also show module that is requiring each duplicate package (default: false)
                verbose: true,
                // Emit errors instead of warnings (default: false)
                emitError: true,
                // Show help message if duplicate packages are found (default: true)
                showHelp: true,
                // Warn also if major versions differ (default: true)
                strict: true,
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
                        name: assetBaseDir + 'media/[name].[hash:8].[ext]',
                    },
                },
                // 'url' loader works just like 'file' loader but it also embeds
                // assets smaller than specified size as data URLs to avoid requests.
                {
                    test: [/\.bmp$/, /\.gif$/, /\.jpe?g$/, /\.png$/],
                    loader: require.resolve('url-loader'),
                    options: {
                        limit: 10 * 1024,
                        name: assetBaseDir + 'media/[name].[hash:8].[ext]',
                    },
                },
                // Process JS with Babel.
                {
                    test: /\.(js|jsx)$/,
                    // HACK - primarily for IE11
                    //
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
                    loader: require.resolve('babel-loader'),
                    options: {
                        // This is a feature of `babel-loader` for webpack (not Babel itself).
                        // It enables caching results in ./node_modules/.cache/babel-loader/
                        // directory for faster rebuilds.
                        cacheDirectory: true,
                        // Uses the babel.config.js in the project root
                        rootMode: 'upward',
                    },
                },
                // ** STOP ** Are you adding a new loader?
                // Remember to add the new extension(s) to the 'file' loader exclusion list.
            ],
        },
        // Some libraries import Node modules but don't use them in the browser.
        // Tell Webpack to provide empty mocks for them so importing them works.
        node: SERVER ? false : {
            dgram: 'empty',
            fs: 'empty',
            net: 'empty',
            tls: 'empty',
        },
    }
}
