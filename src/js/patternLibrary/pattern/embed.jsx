import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

class Embed extends Component {
    render () {
        let testProps = _.toArray(
            _.map(_.omit(this.props.component.component.testProps, ['children']) || {}, (value, name) => {
                return name + '=' + JSON.stringify(value)
            })
        ).join(' ')

        let component = ''
        if (this.props.component.component.testProps && this.props.component.component.testProps.children) {
            component = `&lt;${this.props.component.name} ${testProps}&gt;
                ${String(this.props.component.component.testProps.children)}
            &lt;/${this.props.component.name}&gt;`
        } else {
            component = `&lt;${this.props.component.name} ${testProps} /&gt;`
        }

        return (<pre className='c-patterns__code'>
            <code dangerouslySetInnerHTML={{ __html: `
import React, { Component, Fragment } from 'react'

import ${this.props.component.name} from '${this.props.component.path}'

class MyComponent extends Component {
    render () {
        return (&lt;Fragment&gt;
            ${component}
        &lt;/Fragment&gt;)
    }
}

MyComponent.propTypes = {
}

MyComponent.defaultProps = {
}

export default MyComponent` }} />
        </pre>)
    }
}

Embed.propTypes = {
    component: PropTypes.any.isRequired,
}

Embed.defaultProps = {
}

export default Embed
