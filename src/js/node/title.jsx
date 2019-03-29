import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'

import { getTranslation } from 'frontastic-common'
import ComponentInjector from '../app/injector'

class Title extends Component {
    render () {
        return (<Helmet titleTemplate='%s | Built on Frontastic'>
            <title>{this.generateTitle()}</title>
        </Helmet>)
    }

    generateTitle = () => {
        if (this.props.node.configuration.seoTitle) {
            return getTranslation(
                this.props.node.configuration.seoTitle,
                this.props.context.locale,
                this.props.context.project.defaultLanguage
            ).text
        }

        if (this.props.node.name) {
            return this.props.node.name
        }

        return this.props.node.configuration.path || ''
    }
}

Title.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Title.defaultProps = {}

export default ComponentInjector.return('Node.Meta.Title', Title)
