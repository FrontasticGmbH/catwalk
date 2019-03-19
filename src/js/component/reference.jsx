import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Link from '../app/link'
import { isReferenceAbsoluteHttpOrMailtoLink } from '../app/referenceHelper'

/**
 * Provides a Link to a Reference (which means either a link to an external site or a node).
 */
class Reference extends Component {
    render () {
        const { reference, children, className, style, target } = this.props
        if (!reference.type) {
            return <Fragment>{children}</Fragment>
        }

        if (isReferenceAbsoluteHttpOrMailtoLink(reference)) {
            return (
                <a href={reference.target} className={className} style={style} target={target}>
                    {children}
                </a>
            )
        }

        return (
            <Link className={className} {...this.linkProps()} style={style} target={target}>
                {children}
            </Link>
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
    className: PropTypes.string,
    style: PropTypes.object,
    target: PropTypes.string,
}

Reference.defaultProps = {}

export default Reference
