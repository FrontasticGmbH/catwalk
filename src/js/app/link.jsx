import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Link } from 'react-router-dom'
import { connect } from 'react-redux'
import _ from 'lodash'

import app from './app'

class SymfonyLink extends Component {
    render () {
        let childProps = _.omit(this.props, ['route', 'params', 'router', 'dispatch'])
        let params = _.extend({}, this.props.params)

        if (this.props.to || this.props.href) {
            throw new Error("Use route parameter with Symfony route ID to link '" + (this.props.to || this.props.href) + "'")
        }

        return (<Link
            to={this.props.path || this.props.router.path(this.props.route, params)}
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
