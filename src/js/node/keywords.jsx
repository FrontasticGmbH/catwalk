import React, { Component } from 'react'
import PropTypes from 'prop-types'

import { Helmet } from 'react-helmet'
import ComponentInjector from '../app/injector'

class Keywords extends Component {
    render () {
        return (this.props.node.configuration.seoKeywords ?
            <Helmet>
                <meta name='keywords' content={this.props.node.configuration.seoKeywords} />
            </Helmet>
        : null)
    }
}

Keywords.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Keywords.defaultProps = {}

export default ComponentInjector.return('Node.Meta.Keywords', Keywords)
