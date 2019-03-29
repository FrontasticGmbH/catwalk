import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'
import ComponentInjector from '../app/injector'

class Description extends Component {
    render () {
        return (<Helmet>
            <meta name='description' content={this.generateDescription()} />
        </Helmet>)
    }

    generateDescription = () => {
        return this.props.node.configuration.seoDescription || ''
    }
}

Description.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Description.defaultProps = {}

export default ComponentInjector.return('Node.Meta.Description', Description)
