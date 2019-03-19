import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import ErrorBoundary from '../app/errorBoundary'
import tasticDataConnector from './tasticDataConnector'

class Tastic extends Component {
    tastics = (typeof window !== 'undefined') ? window.tastics : global.tastics

    render () {
        let tastic = this.props.tastic

        if (!this.tastics[tastic.tasticType]) {
            return (<div className='alert alert-warning'>
                <p>Tastic {tastic.tasticType} not yet implemented.</p>
                <p>Did you just implement it? Please don't forget to register it in the <code>tastic/tastics.js</code>!</p>
            </div>)
        }
        let Tastic = this.tastics[tastic.tasticType]

        for (let tasticIndex in this.tastics) {
            if (typeof this.tastics[tasticIndex] !== 'function') {
                console.warn('Type of Tastic "' + tasticIndex + '" is "' + (typeof this.tastics[tasticIndex]) + '" ' +
                    'but expected "function". ' +
                    'Maybe you included the JSON definition instead of the JSX component in tastics.js?'
                )
            }
        }

        return (<ErrorBoundary>
            <div className={'e-tastic ' +
                    'e-tastic__' + tastic.tasticType + ' ' +
                    (tastic.schema.get('mobile') ? '' : 'e-tastic--hidden-hand ') +
                    (tastic.schema.get('tablet') ? '' : 'e-tastic--hidden-lap ') +
                    (tastic.schema.get('desktop') ? '' : 'e-tastic--hidden-desk ')
                }
                style={{
                    outline: (_.isEqual(tastic.tasticId, this.props.highlight) ? '2px dashed #d73964' : null),
                }}
                id={tastic.tasticId}>
                <Tastic tastic={tastic} node={this.props.node} page={this.props.page} rawData={this.props.data} data={this.props.resolved} />
            </div>
        </ErrorBoundary>)
    }
}

Tastic.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    resolved: PropTypes.object.isRequired,
    highlight: PropTypes.any,
}

Tastic.defaultProps = {
}

export default connect(tasticDataConnector)(Tastic)
