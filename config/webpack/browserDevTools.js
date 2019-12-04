const merge = require('webpack-merge')

module.exports = (config, PRODUCTION, SERVER) => {
    return merge.smart(
        {
            entry: [
                // Include an alternative client for WebpackDevServer. A client's
                // job is to connect to WebpackDevServer by a socket and get
                // notified about changes.  When you save a file, the client will
                // either apply hot updates (in case of CSS changes), or refresh
                // the page (in case of JS changes). When you make a syntax error,
                // this client will display a syntax error overlay.  Note: instead
                // of the default WebpackDevServer client, we use a custom one to
                // bring better experience for Create React App users.
                require.resolve('react-dev-utils/webpackHotDevClient'),
                // Errors should be considered fatal in development
                require.resolve('react-error-overlay'),
            ]
        },
        config,
    )
}
