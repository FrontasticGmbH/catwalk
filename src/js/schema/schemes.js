import NodeSchema from './nodeSchema'

import ComponentInjector from '../app/injector'

const schemes = {
    node: ComponentInjector.return('NodeConfigurationSchema', NodeSchema),
}

export default schemes
