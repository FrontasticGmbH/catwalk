const baseConfig = require('./webpack.config.dev.js')
const paths = require('./paths')
const webpack = require('webpack')
const NodemonPlugin = require('nodemon-webpack-plugin')
const webpackExcludes = require('./webpackExclude')

let serverConfig = {
    ...baseConfig,
    name: 'server',
    mode: 'development',
    target: 'node',
    entry: [require.resolve('./polyfills'), paths.serverIndexJs],
    module: {
        strictExportPresence: true,
        rules: [
            // ** ADDING/UPDATING LOADERS **
            // The "file" loader handles all assets unless explicitly excluded.
            // The `exclude` list *must* be updated with every change to loader extensions.
            // When adding a new loader, you must add its `test`
            // as a new entry in the `exclude` list for "file" loader.

            // "file" loader makes sure those assets get served by WebpackDevServer.
            // When you `import` an asset, you get its (virtual) filename.
            // In production, they would get copied to the `build` folder.
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
                    /\.css$/,
                    /\.scss$/,
                ],
                loader: require.resolve('file-loader'),
                options: {
                    name: 'webpack/media/[name].[hash:8].[ext]',
                },
            },
            // "url" loader works like "file" loader except that it embeds assets
            // smaller than specified limit in bytes as data URLs to avoid requests.
            // A missing `test` is equivalent to a match.
            {
                test: [/\.bmp$/, /\.gif$/, /\.jpe?g$/, /\.png$/],
                loader: require.resolve('url-loader'),
                options: {
                    limit: 10000,
                    name: 'webpack/media/[name].[hash:8].[ext]',
                },
            },
            // Process JS with Babel.
            {
                test: /\.(js|jsx)$/,
                // On windows it can happen that the frontastic packages are
                // not linked but copied. In this case babel should still
                // compile the files in those folders.
                use: {
                    loader: require.resolve('babel-loader'),
                    options: {
                        // This is a feature of `babel-loader` for webpack (not Babel itself).
                        // It enables caching results in ./node_modules/.cache/babel-loader/
                        // directory for faster rebuilds.
                        cacheDirectory: true,
                        presets: [['@babel/preset-env', { modules: false }], '@babel/preset-react'],
                        plugins: [
                            '@babel/plugin-proposal-class-properties',
                            '@babel/plugin-syntax-dynamic-import',
                            'lodash',
                            ['babel-plugin-transform-require-ignore', { extensions: ['.less', '.sass', '.css'] }],
                        ],
                    },
                },
            },
            // ** STOP ** Are you adding a new loader?
            // Remember to add the new extension(s) to the "file" loader exclusion list.
        ],
    },
    plugins: [
        // Makes some environment variables available to the JS code, for example:
        // if (process.env.NODE_ENV === 'production') { ... }. See `./env.js`.
        // It is absolutely essential that NODE_ENV was set to production here.
        // Otherwise React will be compiled in the very slow development mode.
        new webpack.DefinePlugin({
            PRODUCTION: JSON.stringify(false),
            'process.env.NODE_ENV': '"development"',
        }),

        new webpack.IgnorePlugin(/^\.css$/, /\.scss$/),

        new webpack.ProvidePlugin({
            document: 'min-document',
            self: 'node-noop',
            'self.navigator.userAgent': 'empty-string',
            'window.navigator.userAgent': 'empty-string',
            'window.navigation.userAgent': 'empty-string',
            'navigator.userAgent': 'empty-string',
            window: 'node-noop',
            location: 'node-noop',
            'window.location.href': 'empty-string',
            'window.location': 'node-noop',
            hostname: 'node-noop',
        }),

        // Moment.js is an extremely popular library that bundles large locale files
        // by default due to how Webpack interprets its code. This is a practical
        // solution that requires the user to opt into importing specific locales.
        // https://github.com/jmblog/how-to-optimize-momentjs-with-webpack
        // You can remove this if you don't use Moment.js:
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),

        new NodemonPlugin({
            // What to watch.
            watch: [paths.appSrc, paths.appSrc + '../../catwalk/src'],

            // Files to ignore.
            ignore: ['*.js.map'],

            // Detailed log.
            verbose: true,

            // If using more than one entry, you can specify
            // which output file will be restarted.
            script: 'build/assets/js/devServer.js',

            // Extensions to watch
            ext: 'js,jsx',
        }),
    ],
    output: {
        ...baseConfig.output,
        filename: 'assets/js/devServer.js',
    },
}

module.exports = serverConfig
