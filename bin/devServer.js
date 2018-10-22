/**
 * We use this file to include the main server and babel-ify the
 * setup for development. nodemon will watch the files for changes
 * and babel-node will transpile the source code and execute it
 * during development.
 *
 * For production build webpack (using babel) is used which ignore
 * the asset files using an ignore-loader.
 *
 * * Babel does not know about ignore loader
 * * Webpack does not support extension.register
 *
 * This is why we use two different ways to build the server-side
 * React. If there is a way of unifying the builds we should do
 * this for increased cionfidence in the production builds.
 */

/* eslint-disable import/first */
import register from 'ignore-styles'
register(['.sass', '.scss', '.svg', '.png', '.css'])

import '../src/js/server.js'
