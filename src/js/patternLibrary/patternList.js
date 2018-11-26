import _ from 'lodash'
import { mapFile } from './functions'

import ComponentInjector from '../app/injector'

const loader = require.context('../patterns/', true, /^(.*\.jsx)[^.]*$/im)
let patterns = {}
loader.keys().forEach(function (fileName) {
    patterns = mapFile(patterns, fileName, loader)
})

export default _.merge(
    patterns,
    ComponentInjector.get('patterns') || {}
)
