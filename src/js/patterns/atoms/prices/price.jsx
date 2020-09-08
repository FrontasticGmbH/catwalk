import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import classnames from 'classnames'

import ComponentInjector from '../../../app/injector'

class AtomsPrice extends Component {
    locale = () => {
        return (this.props.context.locale).replace('_', '-')
    }

    render () {
        const Component = this.props.old ? 'del' : 'span'

        return (<Component
            itemProp='price'
            className={classnames({
                'c-price': true,
                'c-price--highlight': this.props.highlight,
                'c-price--old': this.props.old,
            })}>
            {(this.props.forceSign && this.props.value > 0 ? '+' : '') +
            (this.props.value / 100).toLocaleString(
                this.locale(),
                {
                    style: 'currency',
                    currency: this.props.currency || this.props.context.currency,
                }
            )}
        </Component>)
    }
}

AtomsPrice.propTypes = {
    context: PropTypes.object.isRequired,
    value: PropTypes.number.isRequired,
    currency: PropTypes.string,
    forceSign: PropTypes.bool,
    highlight: PropTypes.bool,
    old: PropTypes.bool,
}

AtomsPrice.defaultProps = {
    currency: null,
    forceSign: false,
    highlight: false,
    old: false,
}

export default connect(
    (globalState, props) => {
        return {
            context: globalState.app.context,
            ...props,
        }
    }
)(ComponentInjector.return('AtomsPrice', AtomsPrice))
