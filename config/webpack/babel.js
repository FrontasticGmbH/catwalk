const { merge } = require('webpack-merge')
const path = require('path')

// @TODO: Make this more readable and especially extensible:
const ie11packages = require('../ie11packages')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge(
        config,
        {
            module: {
                rules: [
                    // Process JS with Babel.
                    {
                        test: /\.(mjs|js|jsx)$/,
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
                            // Added due to the way babel resolves domino modules on Windows, otherwise
                            // babel forces strict mode and and it crashes due to with statements in
                            // domino/lib/.../sloppy.js
                            overrides: [{
                                test: /domino\\lib/,
                                sourceType: "unambiguous",
                            }],
                        },
                    },
                    // Process TypeScript with Babel.
                    {
                        test: /\.(ts)$/,
                        loader: require.resolve('ts-loader'),
                        options: { allowTsInNodeModules: true },
                    },
                    {
                        test: /\.(tsx)$/,
                        use: [
                            require.resolve('babel-loader'),
                            {
                                loader: require.resolve('ts-loader'),
                                options: { allowTsInNodeModules: true },
                            }
                        ],
                    },
                ],
            },
        },
    )
}
