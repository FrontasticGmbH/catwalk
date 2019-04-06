import * as React from 'react'
import PropTypes from 'prop-types'

import UICell from '../patterns/atoms/grid/cell'
import Tastic from './tastic'

const insertIf = (cond, ...els) => (cond ? els : [])

class Cell extends React.Component {
    render() {
        let cell = this.props.cell
        let columns = cell.schema.get('size') || '12'

        return (
            <UICell
                size={columns}
                hideOn={[
                    ...insertIf(!cell.schema.get('mobile'), 'hand'),
                    ...insertIf(!cell.schema.get('tablet'), 'lap'),
                    ...insertIf(!cell.schema.get('desktop'), 'desk'),
                ]}
                style={{
                    outline: cell.cellId === this.props.highlight ? '2px dashed #30a0bf' : null,
                }}
            >
                {cell && cell.tastics && cell.tastics.length
                    ? cell.tastics.map((tastic, i) => {
                          return (
                              <Tastic
                                  key={tastic.tasticId}
                                  highlight={this.props.highlight}
                                  node={this.props.node}
                                  page={this.props.page}
                                  data={this.props.data}
                                  region={this.props.region}
                                  cell={cell}
                                  tastic={tastic}
                              />
                          )
                      })
                    : null}
            </UICell>
        )
    }
}

Cell.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    region: PropTypes.object.isRequired,
    cell: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    highlight: PropTypes.any,
}

Cell.defaultProps = {
    highlight: null,
}

export default Cell
