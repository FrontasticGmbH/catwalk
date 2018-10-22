const path = require('path');
const {merge} = require('lodash');
const globby = require('globby');
const rollup = require('rollup');
const babel = require('rollup-plugin-babel');
const eslint = require('rollup-plugin-eslint');
const resolve = require('rollup-plugin-node-resolve');
const commonjs = require('rollup-plugin-commonjs');
const replace = require('rollup-plugin-replace');
const uglify = require('rollup-plugin-uglify');

const commonConfig = {
    plugins: [
        replace({
            exclude: 'node_modules/**',
            __ENV: (process.env.NODE_ENV || 'development')
        }),
        resolve({
            jsnext: true,
            main: true,
            browser: true,
        }),
        commonjs(),
        eslint({
            exclude: [
                'src/styles/**',
            ],
            parserOptions: {
                ecmaVersion: 6,
                sourceType: 'module',
                ecmaFeatures: {
                    experimentalObjectRestSpread: true
                }
            }
        }),
        babel({
            babelrc: false,
            exclude: "node_modules/**",
            externalHelpers: true,
            presets: [
                ["@babel/preset-env", {
                    "targets": ["last 2 versions"],
                    "modules": false,
                    "useBuiltIns": "usage"
                }]
            ]
        }),
        (process.env.NODE_ENV === 'production' && uglify({
            warnings: true,
            mangle: true,
            compress: {
                drop_console: true
            },
            output: {
                comments: (node, comment) => {
                    const {value: text, type} = comment;
                    if (type === 'comment2') {
                        return /^\/*!|@preserve|@license|@cc_on/i.test(text);
                    }
                }
            }
        }))
    ],
    output: {
        format: 'iife',
        sourcemap: process.env.NODE_ENV === 'development'
            ? 'inline'
            : false
    }
};
const configs = async () => {
    const filepaths = await globby([path.join(process.env.XRD_SRC_JS, '*.js')]);
    return filepaths.map(filepath => ({
        input: filepath,
        output: {
            file: path.join(process.env.XRD_PUBLIC_JS, path.basename(filepath))
        }
    }));
};

async function build() {
    const opts = await configs();
    const bundles = opts.map(async (o) => {
        const config = merge(o, commonConfig);
        const bundle = await rollup.rollup(config);
        return await bundle.write(config.output);
    });
    return await Promise.all(bundles);
}

module.exports = build;
