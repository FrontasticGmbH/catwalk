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
const SERVER = false

// Assert this just to be safe.
// Development builds of React are slow and not intended for production.
if (env.stringified['process.env'].NODE_ENV !== '"production"') {
    throw new Error('Production builds must have NODE_ENV=production.')
}

// Note: defined here because it will be used more than once.
const cssFilename = 'assets/css/[name].[contenthash:8].css'

// ExtractTextPlugin expects the build output to be flat.
// (See https://github.com/webpack-contrib/extract-text-webpack-plugin/issues/27)
// However, our output is structured with css, js and media folders.
// To have this structure working with relative paths, we have to use custom options.
const extractTextPluginOptions = shouldUseRelativeAssetPaths
    ? // Making sure that the publicPath goes back to to build folder.
    { publicPath: Array(cssFilename.split('/').length).join('../') }
    : {}

// This is the production configuration.
// It compiles slowly and is focused on producing a fast and minimal bundle.
// The development configuration is different and lives in a separate file.
module.exports = {
    name: 'main',
    target: 'web',
    // Don't attempt to continue if there are any errors.
    mode: PRODUCTION ? 'production' : 'development',
    bail: true,
    // We generate sourcemaps in production. This is slow but gives good results.
    // You can exclude the *.map files from the build during deployment.
    devtool: 'source-map',
    // In production, we only want to load the polyfills and the app code.
    entry: [
        // We ship a few polyfills by default:
        require.resolve('./polyfills'),
        // Finally, this is your app's code:
        paths.appIndexJs,
        // We include the app code last so that if there is a runtime error during
        // initialization, it doesn't blow up the WebpackDevServer client, and
        // changing JS code would still trigger a refresh.
    ],
    output: {
        // The build folder.
        path: paths.appBuild,
        // Generated JS file names (with nested folders).
        // There will be one main bundle, and one file per asynchronous chunk.
        // We don't currently advertise code splitting but Webpack supports it.
        filename: 'assets/js/[name].[chunkhash:8].js',
        chunkFilename: 'assets/js/[name].[chunkhash:8].chunk.js',
        // We inferred the 'public path' (such as / or /my-project) from homepage.
        publicPath: publicPath,
        // Point sourcemap entries to original disk location (format as URL on Windows)
        devtoolModuleFilenameTemplate: (info) => {
            return path.relative(paths.appSrc, info.absoluteResourcePath).replace(/\\/g, '/')
        },
    },
    resolve: {
        // This allows you to set a fallback for where Webpack should look for modules.
        // We placed these paths second because we want `node_modules` to 'win'
        // if there are any conflicts. This matches Node resolution mechanism.
        // https://github.com/facebookincubator/create-react-app/issues/253
        modules: [
            // 'node_modules/frontastic-catwalk/node_modules',
            // 'node_modules/frontastic-common/node_modules',
            paths.appNodeModules,
            path.resolve(__dirname, '../node_modules'),
            path.resolve(__dirname, '../../../node_modules'),
        ].concat(
            // It is guaranteed to exist because we tweak it in `env.js`
            process.env.NODE_PATH.split(path.delimiter).filter(Boolean)
        ),
        // These are the reasonable defaults supported by the Node ecosystem.
        // We also include JSX as a common component filename extension to support
        // some tools, although we do not recommend using it, see:
        // https://github.com/facebookincubator/create-react-app/issues/290
        // `web` extension prefixes have been added for better support
        // for React Native Web.
        extensions: ['.web.js', '.js', '.jsx', '.json', '.web.jsx'],
        alias: {
            // Support React Native Web
            // https://www.smashingmagazine.com/2016/08/a-glimpse-into-the-future-with-react-native-for-web/
            'react-native': 'react-native-web',
        },
        plugins: [],
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

        // Ignore files only used during pattern development
        new webpack.IgnorePlugin(/-ui\.jsx$/),
        // @TODO: Reactivate these. Production should not depend on this, but
        // right now still does:
        // new webpack.IgnorePlugin(/fixture(\.js)?$/),
        // new webpack.IgnorePlugin(/\/templates\//),

        // Extract CSS code statically
        new MiniCssExtractPlugin({
            // Options similar to the same options in webpackOptions.output
            // both options are optional
            filename: 'assets/css/[name].[chunkhash:8].css',
            chunkFilename: 'assets/css/[name].[chunkhash:8].css',
        }),

        // Generate a manifest file which contains a mapping of all asset filenames
        // to their corresponding output file so that tools can pick it up without
        // having to parse `index.html`.
        new ManifestPlugin({
            fileName: 'asset-manifest.json',
        }),

        // Generate a service worker script that will precache, and keep up to date,
        // the HTML & assets that are part of the Webpack build.
        new SWPrecacheWebpackPlugin({
            // By default, a cache-busting query parameter is appended to requests
            // used to populate the caches, to ensure the responses are fresh.
            // If a URL is already hashed by Webpack, then there is no concern
            // about it being stale, and the cache-busting can be skipped.
            dontCacheBustUrlsMatching: /\.\w{8}\./,
            filename: 'service-worker.js',
            staticFileGlobs: ['public/assets/*.png', 'public/assets/*.svg'],
            stripPrefix: 'public',
            mergeStaticsConfig: true,
            logger (message) {
                if (message.indexOf('Total precache size is') === 0) {
                    // This message occurs for every build and is a bit too noisy.
                    return
                }
                if (message.indexOf('Skipping static resource') === 0) {
                    // This message obscures real errors so we ignore it.
                    // https://github.com/facebookincubator/create-react-app/issues/2612
                    return
                }
                console.log(message)
            },
            minify: true,
            // For unknown URLs, fallback to the index page
            navigateFallback: publicUrl + '/index.html',
            // Ignores URLs starting from /__ (useful for Firebase):
            // https://github.com/facebookincubator/create-react-app/issues/2237#issuecomment-302693219
            navigateFallbackWhitelist: [/^(?!\/__).*/],
            // Don't precache sourcemaps (they're large) and build asset manifest:
            staticFileGlobsIgnorePatterns: [/\.map$/, /asset-manifest\.json$/],
        }),

        // Moment.js is an extremely popular library that bundles large locale files
        // by default due to how Webpack interprets its code. This is a practical
        // solution that requires the user to opt into importing specific locales.
        // https://github.com/jmblog/how-to-optimize-momentjs-with-webpack
        // You can remove this if you don't use Moment.js:
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),

        // Anlyze bundle size
        new BundleAnalyzerPlugin({
            reportFilename: paths.appBuild + '/bundleSize.html',
            analyzerMode: 'static',
        }),

        // Webpack dependency graph and other stats as JSON
        new StatsPlugin('bundleStats.json', {
            chunkModules: true,
            exclude: [/node_modules[\\\/]react/],
        }),

        // Show packages which are included from multiple locations, which
        // increases the build size.
        new DuplicatePackageCheckerPlugin({
            // Also show module that is requiring each duplicate package (default: false)
            verbose: true,
            // Emit errors instead of warnings (default: false)
            emitError: true,
            // Show help message if duplicate packages are found (default: true)
            showHelp: false,
            // Warn also if major versions differ (default: true)
            strict: true,
        }),
    ],
    optimization: {
        minimize: true,
        splitChunks: {
            chunks: 'all',
            minSize: 10 * 1024,
            maxSize: 0,
            minChunks: 1,
            automaticNameDelimiter: '~',
            name: true,
            cacheGroups: {
                icons: {
                    minChunks: 1,
                    test: /\/layout\/icons\//,
                    priority: 10,
                },
                vendors: {
                    test: /\/node_modules\//,
                    priority: 5,
                },
                default: {
                    minChunks: 2,
                    priority: -20,
                    reuseExistingChunk: true,
                },
            },
        },
    },
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
            {
                test: /\.css$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: require.resolve('css-loader'), // translates CSS into CommonJS
                    },
                    {
                        loader: require.resolve('postcss-loader'),
                        options: {
                            // Necessary for external CSS imports to work
                            // https://github.com/facebookincubator/create-react-app/issues/2677
                            ident: 'postcss',
                            plugins: () => {
                                return [require('postcss-flexbugs-fixes'), autoprefixer()]
                            },
                        },
                    },
                ],
            },
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: require.resolve('css-loader'), // translates CSS into CommonJS
                    },
                    {
                        loader: require.resolve('postcss-loader'),
                        options: {
                            // Necessary for external CSS imports to work
                            // https://github.com/facebookincubator/create-react-app/issues/2677
                            ident: 'postcss',
                            plugins: () => {
                                return [require('postcss-flexbugs-fixes'), autoprefixer()]
                            },
                        },
                    },
                    // @TODO: This (sadly) does not work at all. Currently
                    // resolved by adding a link catwalk -> paas/catwalk in the
                    // root and still use relative paths to the entry point.
                    {
                        loader: require.resolve('resolve-url-loader'), // Resolve relative url() paths
                    }, //
                    {
                        loader: require.resolve('sass-loader'), // compiles Sass to CSS
                        options: {
                            sourceMap: true, // resolve-url-loader requires a sourceMap, will be skipped afterwards
                        },
                    },
                ],
            },
            // ** STOP ** Are you adding a new loader?
            // Remember to add the new extension(s) to the 'file' loader exclusion list.
        ],
    },
    // Some libraries import Node modules but don't use them in the browser.
    // Tell Webpack to provide empty mocks for them so importing them works.
    node: {
        dgram: 'empty',
        fs: 'empty',
        net: 'empty',
        tls: 'empty',
    },
}
