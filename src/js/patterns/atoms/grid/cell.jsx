import React from 'react'

// TODO: Make class style component
/* eslint-disable react/prop-types */

const calculateCellStyle = (full, wWidth, gWidth) => {
    const ml = (wWidth - gWidth) / 2
    // the toString is  a weird hack to make the first render (SSR)
    // work as expected. The server of course doesn't have any information
    // but somehow this seems to work
    return full ? { width: `${wWidth.toString()}px`, marginLeft: ml * -1 } : {}
}

export default function Cell ({
    size = 12,
    children,
    fullWidth = false,
    windowWidth,
    gridWidth,
    className = '',
    prefix = 'o-cell',
    hideOn = [],
}) {
    // exact same code as in Grid.jsx. Maybe time for a helper?
    let hideOnClasses = ''
    if (!Array.isArray(hideOn)) {
        hideOnClasses = ` ${prefix}--hidden-${hideOn}`
    } else {
        hideOnClasses = hideOn.reduce((acc, crnt) => {
            return acc + ` ${prefix}--hidden-${crnt}`
        }, '')
    }

    return (
        <div
            style={calculateCellStyle(fullWidth, windowWidth, gridWidth)}
            className={`${prefix} ${prefix}--${size}${
                fullWidth ? ` ${prefix}--full` : ''
            } ${className} ${hideOnClasses}`}
            >
            {children}
        </div>
    )
}
