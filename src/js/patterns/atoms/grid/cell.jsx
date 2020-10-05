//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React from 'react'
import PropTypes from 'prop-types'

export default function Cell ({
    size = 12,
    children,
    fullWidth = false,
    className = '',
    prefix = 'o-cell',
    style = {},
    hideOn = [],
}) {
    deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

    // exact same code as in Grid.jsx. Maybe time for a helper?
    let hideOnClasses = ''
    if (!Array.isArray(hideOn)) {
        hideOnClasses = ` ${prefix}--hidden-${hideOn}`
    } else {
        hideOnClasses = hideOn.reduce((acc, crnt) => {
            return acc + ` ${prefix}--hidden-${crnt}`
        }, '')
    }

    // similar code lives also in the boost theme,
    // and even though that's duplication, I try to
    // keep boost and catwalk as decoupled as possible.
    const fullWidthStyle = {
        width: '100vw',
        position: 'relative',
        left: '50%',
        right: '50%',
        marginLeft: '-50vw',
        marginRight: '-50vw',
    }

    return (
        <div
            style={fullWidth ? { ...fullWidthStyle, ...style } : style}
            className={`${prefix} ${prefix}--${size}${
                fullWidth ? ` ${prefix}--full` : ''
            } ${className} ${hideOnClasses}`}
            >
            {children}
        </div>
    )
}

Cell.propTypes = {
    size: PropTypes.oneOf([1, 2, 3, 4, 6, 8, 12]),
    style: PropTypes.object,
    children: PropTypes.node,
    fullWidth: PropTypes.bool,
    className: PropTypes.string,
    prefix: PropTypes.string,
    hideOn: PropTypes.arrayOf(PropTypes.string),
}
