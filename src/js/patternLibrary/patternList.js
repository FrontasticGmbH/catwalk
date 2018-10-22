import _ from 'lodash'
import { displayName } from './functions'

import ComponentInjector from '../app/injector'

const loader = require.context('../patterns/', true, /^(.*\.jsx)[^.]*$/im)
const patterns = {}
loader.keys().forEach(function (fileName) {
    let propertyPath = _.trim(fileName.replace(/(\.?\/\d+-|\.[a-z]+$)/g, '.'), '.')
    _.set(
        patterns,
        propertyPath,
        {
            name: displayName(propertyPath.split('.').pop()),
            path: fileName,
            component: loader(fileName).default,
        }
    )
})

export default _.merge(
    patterns,
    ComponentInjector.get('patterns') || {}
)
