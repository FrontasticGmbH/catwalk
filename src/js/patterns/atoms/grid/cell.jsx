import * as React from 'react'

const cellStyle = (full, wWidth, gWidth) => {
    const ml = (wWidth - gWidth) / 2
    const w = wWidth
    return full ? { marginLeft: ml * -1, width: w } : {}
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
        hideOnClasses = hideOn.reduce((acc, crnt) => { return acc + ` ${prefix}--hidden-${crnt}` }, '')
    }
    return (
        <div
            style={cellStyle(fullWidth, windowWidth, gridWidth)}
            className={`${prefix} ${prefix}--${size}${fullWidth ? ` ${prefix}--full` : ''} ${className} ${hideOnClasses}`}
            >
            {children}
        </div>
    )
}
