import * as React from 'react'
import PropTypes from 'prop-types'

import UICell from '../patterns/atoms/grid/cell'
import Tastic from './tastic'

const insertIf = (cond, ...els) => {
    return cond ? els : []
}

const Cell = ({ node, page, region, cell, data, highlight }) => {
    let columns = cell.schema.get('size') || '12'
    return (
        <UICell
            size={columns}
            hideOn={[
                ...insertIf(!cell.schema.get('mobile'), 'hand'),
                ...insertIf(!cell.schema.get('tablet'), 'tablet'),
                ...insertIf(!cell.schema.get('desktop'), 'desk'),
            ]}
            style={{
                outline: cell.cellId === highlight ? '2px dashed #30a0bf' : null,
            }}
            >
            {cell && cell.tastics && cell.tastics.length
                ? cell.tastics.map((tastic, i) => {
                      if (!tastic || !data) {
                          // eslint-disable-next-line no-console
                          console.error('Could not render tastic, because tastic data was missing')
                          return null
                      }

                      return (
                          <Tastic
                              key={tastic.tasticId}
                              highlight={highlight}
                              node={node}
                              page={page}
                              data={data}
                              region={region}
                              cell={cell}
                              tastic={tastic}
                          />
                      )
                  })
                : null}
        </UICell>
    )
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
