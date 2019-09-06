import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import ErrorBoundary from '../app/errorBoundary'
import tasticDataConnector from './tasticDataConnector'

const TasticWrapper = (props) => {
    const tastics = (window && window.tastics) || (global && global.tastics) || []
    const tastic = props.tastic

    if (!tastics[tastic.tasticType]) {
        if (!props.isDebug) {
            return null
        }

        return (<div className='alert alert-warning'>
            <p>Tastic <code>{tastic.tasticType}</code> not yet implemented.</p>
            <p>Did you just implement it? Please don't forget to register it in the <code>tastic/tastics.js</code>!</p>
        </div>)
    }
    let Tastic = tastics[tastic.tasticType]

    return (<ErrorBoundary isDebug={props.isDebug}>
        <div className={'e-tastic ' +
                'e-tastic__' + tastic.tasticType + ' ' +
                (tastic.schema.get('mobile') ? '' : 'e-tastic--hidden-hand ') +
                (tastic.schema.get('tablet') ? '' : 'e-tastic--hidden-lap ') +
                (tastic.schema.get('desktop') ? '' : 'e-tastic--hidden-desk ')
            }
            style={{
                outline: (_.isEqual(tastic.tasticId, props.highlight) ? '2px dashed #d73964' : null),
            }}
            id={tastic.tasticId}>
            <Tastic tastic={tastic} node={props.node} page={props.page} rawData={props.data} data={props.resolved} />
        </div>
    </ErrorBoundary>)
}

TasticWrapper.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    resolved: PropTypes.object.isRequired,
    highlight: PropTypes.any,
    isDebug: PropTypes.bool,
}

TasticWrapper.defaultProps = {
    isDebug: false,
}

export default connect(tasticDataConnector)(TasticWrapper)
