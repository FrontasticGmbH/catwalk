import React, { useMemo } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import ErrorBoundary from '../app/errorBoundary'
import tasticDataConnector from './tasticDataConnector'
import configurationResolver from '../app/configurationResolver';

const getStreamIdsForTasticSchema = (schema) => {
    return Object.keys(schema.fields)
        .filter(fieldName => {
            return schema.fields[fieldName].type === 'stream'
        })
        .map(fieldName => schema.get(fieldName))
}

const TasticWrapper = (props) => {
    const tastics = (window && window.tastics) || (global && global.tastics) || []
    const tastic = props.tastic

    if (!props.tastic || !props.data) {
        // eslint-disable-next-line no-console
        console.error('Could not render tastic, because tastic data was missing')
        return null;
    }

    const additionalData = (props.data.tastic || {})[props.tastic.tasticId] || null

    const streams = getStreamIdsForTasticSchema(props.tastic.schema)
        .map(streamId => props.data.stream[streamId])

    const tasticData = useMemo(() => {
        return configurationResolver(
            props.tastic.schema,
            props.data.stream,
            additionalData || {}
        )

        // Warning - the dependencies of this useMemo might differ from those that are actually in use,
        // because it makes assumptions about the implementation details of configurationResolver.
        // @TODO This should be improved in the future.
    }, props.tastic.schema, props.data.stream, additionalData, ...streams)

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
            <Tastic tastic={tastic} node={props.node} page={props.page} rawData={props.data} data={tasticData} />
        </div>
    </ErrorBoundary>)
}

TasticWrapper.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    highlight: PropTypes.any,
    isDebug: PropTypes.bool,
}

TasticWrapper.defaultProps = {
    isDebug: false,
}

export default connect((globalState) => {
    return  {
        isDebug: !!(globalState.app.context && globalState.app.context.isDevelopment())
    }
})(TasticWrapper)
