import NodeConfiguration from './nodeConfiguration'

import ComponentInjector from '../app/injector'

const schemes = {
    node: ComponentInjector.return('NodeConfigurationSchema', NodeConfiguration),
}

export default schemes
