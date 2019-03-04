import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Tastic from './tastic'

class Cell extends Component {
    columnMap = {
        '12': 'e-cell--12',
        '8': 'e-cell--8',
        '6': 'e-cell--6',
        '4': 'e-cell--4',
        '3': 'e-cell--3',
        '2': 'e-cell--2',
    }

    render () {
        let cell = this.props.cell
        let columns = cell.schema.get('size') || '12'

        return (<div className={'e-cell ' +
                (cell.schema.get('mobile') ? '' : 'e-cell--hidden-hand ') +
                (cell.schema.get('tablet') ? '' : 'e-cell--hidden-lap ') +
                (cell.schema.get('desktop') ? '' : 'e-cell--hidden-desk ') +
                ' ' + this.columnMap[columns]
            }
            style={{
                margin: '0px',
                padding: '0px',
                float: 'none',
                outline: (cell.cellId === this.props.highlight ? '2px dashed #30a0bf' : null),
            }}>
            {cell && cell.tastics && cell.tastics.length ?
            _.map(cell.tastics, (tastic, i) => {
                return (<Tastic key={tastic.tasticId}
                    highlight={this.props.highlight}
                    node={this.props.node}
                    page={this.props.page}
                    data={this.props.data}
                    region={this.props.region}
                    cell={cell}
                    tastic={tastic}
                />)
            }) : null}
        </div>)
    }
}

Cell.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    region: PropTypes.object.isRequired,
    cell: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    customConfiguration: PropTypes.object.isRequired,
    highlight: PropTypes.any,
}

Cell.defaultProps = {
    highlight: null,
}

export default Cell
