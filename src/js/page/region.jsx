import * as React from 'react'
import PropTypes from 'prop-types'

import { ConfigurationSchema } from 'frontastic-common'
import configurationResolver from '../app/configurationResolver'
import { useDeviceType } from '../helper/hooks/useDeviceType'

import Grid from '../patterns/atoms/grid/grid'
import Row from '../patterns/atoms/grid/row'
import Cell from './cell'
import schemas from '../schemas'

/**
 * Determines which cells are being rendered in which column and row.
 *
 * Because we don't have the concept of "Rows", we need
 * to get a little creative with rendering our one-dimensional
 * long list of cells into proper rows and columns.
 *
 * this function creates a two dimensional array of cells
 * arranged into rows and columns.
 * [
 *  [col1, col2, col3, col4, etc],
 *  [col1, col2-4, col5, etc],
 * ]
 *
 *  We achieve this by just counting the column size of the cells.
 *  Once we hit 12 columns, we add a new row (first level array).
 *
 *  Also, if an element is hidden via backstagefromn the current
 *  device type (desktop, tablet, mobile) it's not being
 *  added to the array (but columns are being counted anyway, to
 *  not mess up the page arrangement)
 *
 *  @param {object} region
 *  @param {string} currentDeviceType
 */
function createRenderTree (region, currentDeviceType) {
    let colsPerRow = 0
    let row = 0 // we start at row 0
    let renderTree = [[]]

    // check if this region has any elements to render
    if (region && region.elements && region.elements.length) {
        const els = region.elements

        // iterate over all elements
        for (let i = 0; i < els.length; i++) {
            // is there already an array for the current row?
            if (!renderTree[row]) {
                renderTree[row] = []
            }

            // push cell to nested array, only if the cell
            // in backstage is set to true for the current device type
            if (els[i].schema.get(currentDeviceType)) {
                renderTree[row].push(els[i])
            }

            // increment the current column by the size of the cell
            // or use the default 12 for full width row
            colsPerRow += els[i].schema.get('size') || '12'

            // is the current row full (or exceeded)? add new row, reset counter.
            if (colsPerRow >= 12) {
                row++
                colsPerRow = 0
            }
        }
    }
    return renderTree
}

function Region ({ node, page, region, data, highlight }) {
    const deviceType = useDeviceType()
    if (!region) {
        return null
    }

    // aranges all elements to be rendered into rows based on
    // column sizes and widths of the elements. Every 12 columns,
    // put the next element into the next row
    const renderTree = createRenderTree(region, deviceType)

    return (
        <Grid
            className='o-region'
            prefix='o-region'
            style={{
                outline: region.regionId === highlight ? '2px dashed #a1d8e7' : null,
            }}
            >
            {renderTree
                .filter((group) => {
                    return group.length > 0
                })
                .map((group, i) => {
                    return (
                        <Row key={`row_${i}`}>
                            {/* row is just a shortcut of a new 12-col grid */}
                            {group.map((cell) => {
                                const customConfiguration = configurationResolver(
                                    new ConfigurationSchema(schemas.cell.schema, cell.customConfiguration || {}),
                                    (data.data || {}).stream || {}
                                )

                                return (
                                    <Cell
                                        key={cell.cellId}
                                        highlight={highlight}
                                        node={node}
                                        page={page}
                                        data={data}
                                        region={region}
                                        cell={cell}
                                        customConfiguration={customConfiguration}
                                    />
                                )
                            })}
                        </Row>
                    )
                })}
        </Grid>
    )
}

Region.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    region: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    highlight: PropTypes.any,
}

Region.defaultProps = {
    highlight: null,
}

export default Region
