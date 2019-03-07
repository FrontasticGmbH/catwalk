import * as React from 'react'
import PropTypes from 'prop-types'

import { ConfigurationSchema } from 'frontastic-common'
import configurationResolver from '../app/configurationResolver'

import Grid from '../patterns/atoms/grid/grid'
import Cell from './cell'
import schemas from '../schemas'

const insertIf = (cond, ...els) => (cond ? els : [])

class Region extends React.Component {
    render() {
        let region = this.props.region
        if (!region) {
            return null
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
                {region && region.elements && region.elements.length
                    ? region.elements.map((cell, i) => {
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
                      })
                    : null}
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
