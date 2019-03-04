import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import { ConfigurationSchema } from 'frontastic-common'
import configurationResolver from '../app/configurationResolver'

import Cell from './cell'
import schemas from '../schemas'

class Region extends Component {
    render () {
        let region = this.props.region
        if (!region) {
            return null
        }

        return (<div className={'e-region ' +
                (region.schema.get('mobile') ? '' : 'e-region--hidden-hand ') +
                (region.schema.get('tablet') ? '' : 'e-region--hidden-lap ') +
                (region.schema.get('desktop') ? '' : 'e-region--hidden-desk ')
            }
            style={{
                display: 'flex',
                // Do those settings make any sense anyways?
                flexDirection: 'row', // region.schema.get('flexDirection'),
                flexWrap: 'wrap', // region.schema.get('flexWrap'),
                justifyContent: region.schema.get('justifyContent'),
                alignItems: region.schema.get('alignItems'),
                alignContent: region.schema.get('alignContent'),
                outline: (region.regionId === this.props.highlight ? '2px dashed #a1d8e7' : null),
            }}>
            {region && region.elements && region.elements.length ?
            _.map(region.elements, (cell, i) => {
                const customConfiguration = configurationResolver(
                    new ConfigurationSchema(schemas.cell.schema, cell.customConfiguration || {}),
                    (this.props.data.data || {}).stream || {}
                )

                return (<Cell key={cell.cellId}
                    highlight={this.props.highlight}
                    node={this.props.node}
                    page={this.props.page}
                    data={this.props.data}
                    region={region}
                    cell={cell}
                    customConfiguration={customConfiguration}
                />)
            }) : null}
        </div>)
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
