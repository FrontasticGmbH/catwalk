import * as React from 'react'
import Grid from './grid'
import Cell from './cell'

// TODO: Make class style component
/* eslint-disable react/prop-types */

export default function Row ({ children }) {
    return (
        <Cell size={12}>
            <Grid>{children}</Grid>
        </Cell>
    )
}
