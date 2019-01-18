import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { Helmet } from 'react-helmet'
import { getTranslation } from 'frontastic-common'

class MetaData extends Component {
    render () {
        // TODO: Make title template configurable
        return (<Fragment>
            <Helmet titleTemplate='%s | Apollo'>
                <title>{this.generateTitle()}</title>
                <meta name='description' content={this.generateDescription()} />
            </Helmet>
            {this.props.node.configuration.seoKeywords ?
                <Helmet>
                    <meta name='keywords' content={this.props.node.configuration.seoKeywords} />
                </Helmet>
            : null}
        </Fragment>)
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

    generateDescription = () => {
        return this.props.node.configuration.seoDescription || ''
    }
}

MetaData.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

MetaData.defaultProps = {}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(MetaData)
