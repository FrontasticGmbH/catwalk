import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'
import { ConfigurationSchema } from 'frontastic-common'

import ComponentInjector from '../app/injector'
import generateTitle from './generateTitle'
import getTranslation from '../getTranslation'

const TITLE_PREFIX_FIELD = 'frontasticPageTitlePrefix'
const TITLE_SUFFIX_FIELD = 'frontasticPageTitleSuffix'

/*
 * These defaults are only used if the corresponding fields are missing from the schema. If the fields are defined in
 * the schema, the defaults from the schema will be used.
 */
const DEFAULT_TITLE_PREFIX = ''
const DEFAULT_TITLE_SUFFIX = ' | Built on Frontastic'

class Title extends Component {
    getSchemaValue = (projectSchema, field, defaultValue) => {
        if (!projectSchema.has(field)) {
            return defaultValue
        }

        return getTranslation(
            projectSchema.get(field),
            this.props.context.locale,
            this.props.context.project.defaultLanguage
        ).text
    }

    getTitle = () => {
        const projectSchema = new ConfigurationSchema(
            this.props.context.projectConfigurationSchema,
            this.props.context.projectConfiguration)

        return this.getSchemaValue(projectSchema, TITLE_PREFIX_FIELD, DEFAULT_TITLE_PREFIX) +
            generateTitle(this.props.node, this.props.context) +
            this.getSchemaValue(projectSchema, TITLE_SUFFIX_FIELD, DEFAULT_TITLE_SUFFIX)
    }

    render () {
        return (<Helmet>
            <title>{this.getTitle()}</title>
        </Helmet>)
    }
}

Title.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Title.defaultProps = {}

export default ComponentInjector.return('Node.Meta.Title', Title)
