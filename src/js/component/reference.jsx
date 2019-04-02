import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

import Link from '../app/link'
import { isReferenceAbsoluteHttpOrMailtoLink } from '../app/referenceHelper'

/**
 * Provides a Link to a Reference (which means either a link to an external site or a node).
 */
class Reference extends Component {
    render () {
        const { reference, children, className, style } = this.props
        if (!reference.type) {
            return <Fragment>{children}</Fragment>
        }

        if (isReferenceAbsoluteHttpOrMailtoLink(reference)) {
            return (
                <a href={reference.target} className={className} {...this.targetProps()} style={style}>
                    {children}
                </a>
            )
        }

        return (
            <Link className={className} {...this.linkProps()} {...this.targetProps()} style={style}>
                {children}
            </Link>
        )
    }

    targetProps = () => {
        if (this.props.target === '_blank' || this.props.reference.mode === 'new_window') {
            return {
                target: '_blank',
                rel: 'noopener',
            }
        }

        return {}
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
                params: this.props.reference.parameters || {},
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
