import configurationResolver from '../app/configurationResolver'

export default (globalState, props) => {
    if (!props.tastic || !props.data) {
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
        isDebug: !!props.context && (props.context.environment === 'dev'),
        resolved: resolved,
    }
}
