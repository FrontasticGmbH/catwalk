import configurationResolver from '../app/configurationResolver'

export default (globalState, props) => {
    if (!props.tastic || !props.data) {
        // eslint-disable-next-line no-console
        console.error('Could not connect Tastic data.')
        return {
            ...props,
            resolved: {},
        }
    }

    // Resilience for data loading problems and error pages
    if (!props.data.stream) {
        props.data.stream = {}
    }

    /** @var Tastic props.tastic **/

    const resolved = configurationResolver(
        props.tastic.schema,
        props.data.stream,
        (props.data.tastic || {})[props.tastic.tasticId] || {}
    )

    return {
        ...props,
        isDebug: !!(globalState.app.context && globalState.app.context.isDevelopment()),
        resolved: resolved,
    }
}
