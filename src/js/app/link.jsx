import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Link } from 'react-router-dom'
import { connect } from 'react-redux'

import omit from '@frontastic/common/src/js/helper/omit'
import app from './app'

class SymfonyLink extends Component {
    render () {
        const childPropsToOmit = ['route', 'params', 'router', 'dispatch']
        const params = { ...this.props.params }
        let path = this.props.path

        if (this.props.to || this.props.href) {
            // eslint-disable-next-line no-console
            console.error("Use route parameter with Symfony route ID to link '" + (this.props.to || this.props.href) + "'")
        }

        if (!path) {
            childPropsToOmit.push('path') // do not render empty path attributes

            if (this.props.router.hasRoute(this.props.route)) {
                path = this.props.router.path(this.props.route, params)
            }
        }

        const childProps = omit(this.props, childPropsToOmit)

        return (<Link
            to={path || '/__error'}
            {...childProps}>
            {this.props.children}
        </Link>)
    }
}

SymfonyLink.propTypes = {
    router: PropTypes.object.isRequired,
    route: PropTypes.string,
    path: PropTypes.string,
    params: PropTypes.object,
    to: PropTypes.string,
    href: PropTypes.string,
    children: PropTypes.any,
}

SymfonyLink.defaultProps = {
    path: '',
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
)(SymfonyLink)
