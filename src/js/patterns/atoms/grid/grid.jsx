import React, { useRef } from 'react'
import { useSelector } from 'react-redux'
import PropTypes from 'prop-types'

const Grid = ({ debug, style, className, hideOn, prefix, children }) => {
    const _gridRef = useRef()
    const widths = useSelector((state) => {
        return {
            windowWidth: state.renderContext.viewportDimension
                ? state.renderContext.viewportDimension.width
                : window // this should be done better
                    ? window.innerWidth
                    : 1280,
            gridWidth: _gridRef.current ? _gridRef.current.getBoundingClientRect().width : 1280,
        }
    })

    const { windowWidth, gridWidth } = widths
    // exact same code as in Cell.jsx. Maybe time for a helper?
    let hideOnClasses = ''
    if (!Array.isArray(hideOn)) {
        hideOnClasses = ` ${prefix}--hidden-${hideOn}`
    } else {
        hideOnClasses = hideOn.reduce((acc, crnt) => {
            return acc + ` ${prefix}--hidden-${crnt}`
        }, '')
    }
    const childrenmap = React.Children.map(children, (c) => {
        if (typeof c.type === 'string') {
            // if c.type is a string, this is an HTML element, and we do not want to modify those.
            return c
        }

        return React.cloneElement(c, { windowWidth, gridWidth })
    })

    return (
        <div
            className={`o-grid ${debug ? 'o-grid--debug' : ''} ${className} ${hideOnClasses}`}
            style={style}
            ref={_gridRef}
            >
            {childrenmap}
        </div>
    )
}

Grid.propTypes = {
    debug: PropTypes.bool,
    style: PropTypes.object,
    className: PropTypes.string,
    hideOn: PropTypes.oneOfType([PropTypes.string, PropTypes.arrayOf(PropTypes.string)]),
    prefix: PropTypes.string,
    children: PropTypes.oneOfType([PropTypes.arrayOf(PropTypes.node), PropTypes.node]),
}

Grid.defaultProps = {
    debug: false,
    style: {},
    className: '',
    prefix: 'o-grid',
    hideOn: [],
}

export default Grid
