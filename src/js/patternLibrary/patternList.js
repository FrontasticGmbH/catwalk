import _ from 'lodash'
import { processPatterns } from './functions'

import ComponentInjector from '../app/injector'

export default _.merge(
    processPatterns('../patterns/'),
    ComponentInjector.get('patterns') || {}
)
