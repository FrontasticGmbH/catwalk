import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Link from '../app/link'
import {isReferenceAbsoluteHttpLink} from "../app/referenceHelper";

/**
 * Provides a Link to a Reference (which means either a link to an external site or a node).
 */
class Reference extends Component {
    render () {
        if (!this.props.reference.type) {
            return <Fragment>{this.props.children}</Fragment>
        }

        if (isReferenceAbsoluteHttpLink(this.props.reference)) {
            return <a href={this.props.reference.target} className={this.props.className}>{this.props.children}</a>
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
