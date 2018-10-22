import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

class Properties extends Component {
    render () {
        return (<ul className='c-patterns__properties'>
            {_.toArray(_.map(this.props.component.propTypes, (definition, name) => {
                // @TODO: Convert PropTypes back into something readable, if possible
                return (<li key={name}>{name}</li>)
            }))}
        </ul>)
    }
}

Properties.propTypes = {
    component: PropTypes.any.isRequired,
}

Properties.defaultProps = {
}

export default Properties
