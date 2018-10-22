import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Link from '../app/link'

class Reference extends Component {
    render () {
        if (!this.props.reference.type) {
            return <Fragment>{this.props.children}</Fragment>
        }

        return (
            <Link children={this.props.children} {...this.linkProps()} />
        )
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
