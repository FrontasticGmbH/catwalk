import * as React from 'react'
import PropTypes from 'prop-types'

import { ConfigurationSchema } from 'frontastic-common'
import configurationResolver from '../app/configurationResolver'

import Grid from '../patterns/atoms/grid/grid'
import Row from '../patterns/atoms/grid/row'
import Cell from './cell'
import schemas from '../schemas'

const insertIf = (cond, ...els) => { return (cond ? els : []) }

class Region extends React.Component {
    render () {
        let region = this.props.region
        if (!region) {
            return null
        }

        // TBR (to be refactored) *marcel
        // very readable like this (prio) but might be
        // better suited in a function
        let colsPerRow = 0

        let row = 0

        let renderTree = [[]]
        // elements?
        if (region && region.elements && region.elements.length) {
            const els = region.elements
            // iterate
            for (let i = 0; i < els.length; i++) {
                // is nested array?
                if (!renderTree[row]) { renderTree[row] = [] }
                // push cell to nested array
                renderTree[row].push(els[i])
                // add current col size to counter
                colsPerRow += els[i].schema.get('size') || '12'
                // row full (or exceeded)? add new row, reset counter.
                if (colsPerRow >= 12) {
                    row++
                    colsPerRow = 0
                }
            }
        }

        return (
            <Grid
                className='o-region'
                prefix='o-region'
                hideOn={[
                    ...insertIf(!region.schema.get('mobile'), 'hand'),
                    ...insertIf(!region.schema.get('tablet'), 'lap'),
                    ...insertIf(!region.schema.get('desktop'), 'desk'),
                ]}
                style={{
                    outline: region.regionId === this.props.highlight ? '2px dashed #a1d8e7' : null,
                }}
                >
                {renderTree.map((group, i) => {
                    // TODO: fix row key
                    return (
                        <Row key={`row_${i}`}>
                            {group.map((cell) => {
                                const customConfiguration = configurationResolver(
                                    new ConfigurationSchema(schemas.cell.schema, cell.customConfiguration || {}),
                                    (this.props.data.data || {}).stream || {}
                                )

                                return (
                                    <Cell
                                        key={cell.cellId}
                                        highlight={this.props.highlight}
                                        node={this.props.node}
                                        page={this.props.page}
                                        data={this.props.data}
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
