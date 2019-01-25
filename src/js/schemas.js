import { DefaultSchemas } from 'frontastic-common'

import ComponentInjector from './app/injector'

const schemes = {
    node: ComponentInjector.return(
        'NodeConfigurationSchema',
        DefaultSchemas.NodeConfigurationSchema
    ),
}

export default schemes
