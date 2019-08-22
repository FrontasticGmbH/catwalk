import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'

import ComponentInjector from '../app/injector'
import getTranslation from '../getTranslation'

class Description extends Component {
    render () {
        const description = this.generateDescription()

        if (description === '') {
            return null
        }

        return (<Helmet>
            <meta name='description' content={description} />
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
