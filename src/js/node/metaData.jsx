import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { Helmet } from 'react-helmet'

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
            return this.props.node.configuration.seoTitle
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
    page: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
}

MetaData.defaultProps = {}

export default MetaData
