import _ from 'lodash'
import { processPatterns } from './functions'

import ComponentInjector from '../app/injector'

export default ComponentInjector.get('patterns') || processPatterns(
    require.context('../patterns/', true, /^(.*\.jsx)[^.]*$/im),
    'Frontastic'
)
