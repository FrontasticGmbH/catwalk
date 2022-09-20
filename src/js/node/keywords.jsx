import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'
import ComponentInjector from '../app/injector'
import getTranslation from '../getTranslation'

class Keywords extends Component {
    render () {
        const seoKeywords = this.getSeoKeywords()
        if (!seoKeywords) {
            return null
        }

        return (<Helmet>
            <meta name='keywords' content={seoKeywords} />
        </Helmet>)
    }

    getSeoKeywords = () => {
        if (!this.props.node.configuration.seoKeywords) {
            return null
        }

        return getTranslation(
            this.props.node.configuration.seoKeywords,
            this.props.context.locale,
            this.props.context.project.defaultLanguage
        ).text
    }
}

Keywords.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Keywords.defaultProps = {}

export default ComponentInjector.return('Node.Meta.Keywords', Keywords)
