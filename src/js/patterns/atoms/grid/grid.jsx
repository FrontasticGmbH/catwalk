//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React from 'react'
import PropTypes from 'prop-types'

const Grid = ({ debug, style, className, hideOn, prefix, children }) => {
    deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

    // exact same code as in Cell.jsx. Maybe time for a helper?
    let hideOnClasses = ''
    if (!Array.isArray(hideOn)) {
        hideOnClasses = ` ${prefix}--hidden-${hideOn}`
    } else {
        hideOnClasses = hideOn.reduce((acc, crnt) => {
            return acc + ` ${prefix}--hidden-${crnt}`
        }, '')
    }

    return (
        <div className={`o-grid ${debug ? 'o-grid--debug' : ''} ${className} ${hideOnClasses}`} style={style}>
            {children}
        </div>
    )
}

Grid.propTypes = {
    debug: PropTypes.bool,
    style: PropTypes.object,
    className: PropTypes.string,
    hideOn: PropTypes.oneOfType([PropTypes.string, PropTypes.arrayOf(PropTypes.string)]),
    prefix: PropTypes.string,
    children: PropTypes.node,
}

Grid.defaultProps = {
    debug: false,
    style: {},
    className: '',
    prefix: 'o-grid',
    hideOn: [],
}

export default Grid
