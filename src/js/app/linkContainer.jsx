import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import omit from '@frontastic/common/src/js/helper/omit'
import app from './app'

class SymfonyLinkContainer extends Component {
    render () {
        let childProps = omit(this.props, ['route', 'params', 'router', 'dispatch', 'activeKey', 'activeHref'])
        let params = { ...this.props.params }

        if (this.props.to || this.props.href) {
            throw new Error("Use route parameter with Symfony route ID to link '" + (this.props.to || this.props.href) + "'")
        }

        // @TODO: Validate these links actually work properly
        return (<a
            href={this.props.router.path(this.props.route, params)}
            {...childProps}>
            {this.props.children}
        </a>)
    }
}

SymfonyLinkContainer.propTypes = {
    router: PropTypes.object.isRequired,
    route: PropTypes.string,
    params: PropTypes.object,
    to: PropTypes.string,
    href: PropTypes.string,
    children: PropTypes.any,
}

SymfonyLinkContainer.defaultProps = {
    route: null,
    params: {},
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            router: app.getRouter(),
        }
    }
)(SymfonyLinkContainer)
