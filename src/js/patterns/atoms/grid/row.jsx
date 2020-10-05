//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import * as React from 'react'
import Grid from './grid'
import Cell from './cell'

// TODO: Make class style component
/* eslint-disable react/prop-types */

export default function Row ({ children }) {
    deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

    return (
        <Cell size={12}>
            <Grid>{children}</Grid>
        </Cell>
    )
}
