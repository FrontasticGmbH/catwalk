import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'

import ComponentInjector from '../app/injector'
import generateTitle from './generateTitle'

class Title extends Component {
    render () {
        return (<Helmet titleTemplate='%s | Built on Frontastic'>
            <title>{generateTitle(this.props.node, this.props.context)}</title>
        </Helmet>)
    }
}

Title.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Title.defaultProps = {}

export default ComponentInjector.return('Node.Meta.Title', Title)
