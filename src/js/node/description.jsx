import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'

import { getTranslation } from 'frontastic-common'
import ComponentInjector from '../app/injector'

class Description extends Component {
    render () {
        return (<Helmet>
            <meta name='description' content={this.generateDescription()} />
        </Helmet>)
    }

    generateDescription = () => {
        if (!this.props.node.configuration.seoDescription) {
            return ''
        }

        return getTranslation(
            this.props.node.configuration.seoDescription,
            this.props.context.locale,
            this.props.context.project.defaultLanguage
        ).text
    }
}

Description.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Description.defaultProps = {}

export default ComponentInjector.return('Node.Meta.Description', Description)
