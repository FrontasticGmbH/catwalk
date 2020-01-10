import React, { useMemo } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import ErrorBoundary from '../app/errorBoundary'
import configurationResolver from '../app/configurationResolver'
import { useDeviceType } from '../helper/hooks/useDeviceType'

const getStreamIdsForTasticSchema = (schema) => {
    return Object.keys(schema.fields)
        .filter((fieldName) => {
            return schema.fields[fieldName].type === 'stream'
        })
        .map((fieldName) => {
            return schema.get(fieldName)
        })
}

const TasticWrapper = (props) => {
    const deviceType = useDeviceType()

    const tastics = (window && window.tastics) || (global && global.tastics) || []
    const tastic = props.tastic

    const additionalData = (props.data.tastic || {})[props.tastic.tasticId] || null

    const streams = getStreamIdsForTasticSchema(props.tastic.schema).map((streamId) => {
        return props.data.stream[streamId]
    })

    const tasticData = useMemo(() => {
        return configurationResolver(props.tastic.schema, props.data.stream, additionalData || {})

        // Warning - the dependencies of this useMemo might differ from those that are actually in use,
        // because it makes assumptions about the implementation details of configurationResolver.
        // @TODO This should be improved in the future.
        //
        // Additionally, we supress ESLint here, because it says:
        // > React Hook useMemo has a spread element in its dependency array.
        // > This means we can't statically verify whether you've passed the correct dependencies
        // As we are spreading here intentionally to make sure that, whenever a stream changes,
        // the configuration will be updated, we sadly need to suppress the warning here.
        // This should be improved in one of the next refactoring-iterations ;)
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [props.tastic.schema, props.data.stream, additionalData, ...streams])

    if (!tastics[tastic.tasticType]) {
        if (!props.isDebug) {
            return null
        }

        return (
            <div className='alert alert-warning'>
                <p>
                    Tastic <code>{tastic.tasticType}</code> not yet implemented.
                </p>
                <p>
                    Did you just implement it? Please don't forget to register it in the <code>tastic/tastics.js</code>!
                </p>
            </div>
        )
    }
    let Tastic = tastics[tastic.tasticType]

    // Do not render the tastic if it was hidden for this device type
    if (!tasticData[deviceType]) {
        return null
    }

    return (
        <ErrorBoundary isDebug={props.isDebug}>
            <div
                className={
                    'e-tastic ' +
                    'e-tastic__' +
                    tastic.tasticType +
                    ' ' +
                    (tastic.schema.get('mobile') ? '' : 'e-tastic--hidden-hand ') +
                    (tastic.schema.get('tablet') ? '' : 'e-tastic--hidden-lap ') +
                    (tastic.schema.get('desktop') ? '' : 'e-tastic--hidden-desk ')
                }
                style={{
                    outline: _.isEqual(tastic.tasticId, props.highlight) ? '2px dashed #d73964' : null,
                }}
                id={tastic.tasticId}
            >
                <Tastic tastic={tastic} node={props.node} page={props.page} rawData={props.data} data={tasticData} />
            </div>
        </ErrorBoundary>
    )
}

TasticWrapper.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    highlight: PropTypes.any,
    isDebug: PropTypes.bool,
    deviceType: PropTypes.string.isRequired,
}

TasticWrapper.defaultProps = {
    isDebug: false,
}

export default connect((globalState) => {
    return {
        isDebug: !!(globalState.app.context && globalState.app.context.isDevelopment()),
    }
})(TasticWrapper)
