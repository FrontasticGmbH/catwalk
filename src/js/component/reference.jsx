import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Link from '../app/link'

class Reference extends Component {
    render () {
        if (!this.props.reference.type) {
            return <Fragment>{this.props.children}</Fragment>
        }

        if (this.isAbsoluteHttpLink()) {
            return <a href={this.props.reference.target} className={this.props.className}>{this.props.children}</a>
        }

        return (
            <Link children={this.props.children} {...this.linkProps()} />
        )
    }

    isAbsoluteHttpLink = () => {
        if (this.props.reference.type !== 'link') {
            return false
        }

        const target = this.props.reference.target
        return target.startsWith('http://') || target.startsWith('https://')
    }

    linkProps = () => {
        switch (this.props.reference.type) {
        case 'link':
            return {
                path: this.props.reference.target,
            }

        case 'node':
            return {
                route: 'node_' + this.props.reference.target,
            }

        default:
            return {}
        }
    }
}

Reference.propTypes = {
    reference: PropTypes.object.isRequired,
    children: PropTypes.any,
}

Reference.defaultProps = {}

export default Reference
