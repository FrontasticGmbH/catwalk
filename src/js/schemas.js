import { DefaultSchemas } from 'frontastic-common'

import ComponentInjector from './app/injector'

const schemes = {
    node: ComponentInjector.return(
        'NodeConfigurationSchema',
        DefaultSchemas.NodeConfigurationSchema
    ),
    cell: ComponentInjector.return(
        'CellConfigurationSchema',
        DefaultSchemas.CellConfigurationSchema
    ),
}

export default schemes
