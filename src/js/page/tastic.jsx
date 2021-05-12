import React, { useMemo } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import { deprecate } from '@frontastic/common'
import ErrorBoundary from '../app/errorBoundary'
import configurationResolver from '../app/configurationResolver'
import { useDeviceType } from '../helper/hooks/useDeviceType'
import tastify from '../helper/tastify'

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

    const allTasticComponentsMap = (window && window.tastics) || (global && global.tastics) || []
    const tasticToRenderConfiguration = props.tastic

    const streamOrCustomFieldData = (props.data.tastic || {})[props.tastic.tasticId] || null

    const streamIdsUsedByTastic = getStreamIdsForTasticSchema(tasticToRenderConfiguration.schema).map((streamId) => {
        return props.data.stream[streamId]
    })

    const resolvedTasticData = useMemo(() => {
        return configurationResolver(props.tastic.schema, props.data.stream, streamOrCustomFieldData || {})

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
    }, [props.tastic.schema, props.data.stream, streamOrCustomFieldData, tasticToRenderConfiguration.tasticId, ...streamIdsUsedByTastic])

    if (!allTasticComponentsMap[tasticToRenderConfiguration.tasticType]) {
        if (!props.isDebug) {
            return null
        }

        return (
            <div className='alert alert-warning'>
                <p>
                    Tastic <code>{tasticToRenderConfiguration.tasticType}</code> not yet implemented.
                </p>
                <p>
                    Did you just implement it? Please don't forget to register it in the <code>tastic/tastics.js</code>!
                </p>
            </div>
        )
    }
    let Tastic = allTasticComponentsMap[tasticToRenderConfiguration.tasticType]

    // Check that all Tastics are wrapped into tastify() and trigger a
    // deprecation notice otherwise
    let tasticName = Tastic.name
    if (!tasticName && Tastic.WrappedComponent) {
        tasticName = Tastic.WrappedComponent.name
    }
    if (!tasticName) {
        tasticName = tasticToRenderConfiguration.tasticType
    }

    if ((tasticName !== 'WithTranslatedTasticData') &&
        (tasticName !== '_temp') &&
        (typeof Tastic !== 'function' || tasticName !== 'TastifiedTastic')) {
        if (props.autoTastify) {
            Tastic = tastify({ translate: true })(Tastic)
        } else {
            deprecate(`Please wrap the Tastic ${tasticName} into tastify() (@frontastic/catwalk/src/js/helper/tastify / https://docs.frontastic.cloud/article/176-catwalk-performance#Tastify) for better rendering performance`)
        }
    }

    // Do not render the tastic if it was hidden for this device type
    if (!resolvedTasticData[deviceType]) {
        return null
    }

    return (
        <ErrorBoundary isDebug={props.isDebug}>
            <div
                className={
                    'e-tastic ' +
                    'e-tastic__' +
                    tasticToRenderConfiguration.tasticType +
                    ' ' +
                    (tasticToRenderConfiguration.schema.get('mobile') ? '' : 'e-tastic--hidden-hand ') +
                    (tasticToRenderConfiguration.schema.get('tablet') ? '' : 'e-tastic--hidden-lap ') +
                    (tasticToRenderConfiguration.schema.get('desktop') ? '' : 'e-tastic--hidden-desk ')
                }
                style={{
                    outline: tasticToRenderConfiguration.tasticId === props.highlight ? '2px dashed #d73964' : null,
                }}
                id={tasticToRenderConfiguration.tasticId}
            >
                <Tastic tastic={tasticToRenderConfiguration} node={props.node} page={props.page} rawData={props.data} data={resolvedTasticData} />
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
    autoTastify: PropTypes.bool,
}

TasticWrapper.defaultProps = {
    isDebug: false,
    autoTastify: false,
}

export default connect((globalState) => {
    return {
        isDebug: !!(globalState.app.context && globalState.app.context.isDevelopment()),
        autoTastify: !!(globalState.app.context?.project?.data?.autoTastify || false),
    }
})(TasticWrapper)
