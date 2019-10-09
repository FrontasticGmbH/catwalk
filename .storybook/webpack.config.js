const path = require('path')

module.exports = async ({ config, mode }) => {
    config.devtool = 'cheap-eval-source-map'
    config.resolve.modules.push(
        path.resolve(__dirname, '../'),
        path.resolve(__dirname, '../src'),
        path.resolve(__dirname, '../node_modules'),
        path.resolve(__dirname, '../../../node_modules'),
        //path.resolve(__dirname, '../../libraries/common')
    )
    config.module.rules.push(
        {
            test: /\.(js|jsx)$/,
            exclude: /node_modules/,
            use: {
                loader: 'babel-loader',
                options: {
                    rootMode: "upward"
                }
            },
        },

        {
            test: /\.scss$/,
            use: [
                {
                    loader: 'style-loader?singleton',
                    options: {
                        hmr: false,
                    },
                },
                {
                    loader: 'css-loader',
                },
                {
                    loader: 'resolve-url-loader',
                },
                {
                    loader: 'sass-loader',
                    options: {
                        sourceMap: true,
                        sourceMapContents: false,
                    },
                },
            ],
            include: [path.resolve(__dirname, '../src/scss'), path.resolve(__dirname, '.')],
        },

        {
            test: /\.woff2?$|\.otf?$|\.ttf$|\.eot$/,
            loader: 'file-loader',
        }
        //{
        //    test: /\.(png|jpg|gif)$/,
        //    use: [
        //        {
        //            loader: 'file-loader',
        //            options: {},
        //        },
        //    ],
        //}
    )
    config.resolve.alias = {
        ...(config.resolve.alias || {}),
        //'frontastic-common': path.resolve(__dirname, '../../libraries/common'),
    }

    return config
}
