import _ from 'lodash'

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

    const resolved = _.fromPairs(
        _.map(props.tastic.schema.fields, (fieldDefinition, fieldName) => {
            // Simple value handling
            let fieldValue = props.tastic.schema.get(fieldName)

            // Lookup stream data
            if (fieldDefinition.type === 'stream') {
                fieldValue = props.data.stream[fieldValue] || null
            }

            // Lookup special field type values
            if (props.data && props.data.tastic &&
                props.data.tastic[props.tastic.tasticId] &&
                props.data.tastic[props.tastic.tasticId][fieldName]
            ) {
                fieldValue = props.data.tastic[props.tastic.tasticId][fieldName]
            }

            return [fieldName, fieldValue]
        })
    )

    return {
        ...props,
        resolved: resolved,
    }
}
