import _ from 'lodash'
import { processPatterns } from './functions'

import ComponentInjector from '../app/injector'

export default _.merge(
    processPatterns(
        require.context('../patterns/', true, /^(.*\.jsx)[^.]*$/im),
        'Frontastic'
    ),
    ComponentInjector.get('patterns') || {}
)
