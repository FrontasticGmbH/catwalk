import * as React from 'react'
import PropTypes from 'prop-types'

class Grid extends React.Component {
    state = {
        windowWidth: -1,
        gridWidth: -1,
    }

    _gridRef = React.createRef()

    static defaultProps = {
        debug: false,
        style: {},
        className: '',
        prefix: 'o-grid',
        hideOn: [],
    }

    handleResize = () => {
        this.setState({
            windowWidth: window && window.document && window.document.body ? window.document.body.offsetWidth : 1280,
            gridWidth: this._gridRef.current ? this._gridRef.current.getBoundingClientRect().width : 1280,
        })
    }

    componentWillMount () {
        if(!isServer) {
            this.handleResize()
        }
    }
    componentDidMount () {
        if (window) {
            window.addEventListener('resize', this.handleResize)
            this.handleResize()
        }
    }
    componentWillUnmount () {
        if (window) {
            window.removeEventListener('resize', this.handleResize)
        }
    }

    render () {
        const { debug, style, className, hideOn, prefix } = this.props
        const { windowWidth, gridWidth } = this.state

        // exact same code as in Cell.jsx. Maybe time for a helper?
        let hideOnClasses = ''
        if (!Array.isArray(hideOn)) {
            hideOnClasses = ` ${prefix}--hidden-${hideOn}`
        } else {
            hideOnClasses = hideOn.reduce((acc, crnt) => { return acc + ` ${prefix}--hidden-${crnt}` }, '')
        }
        const children = React.Children.map(this.props.children, (c) => {
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
                ref={this._gridRef}
                >
                {children}
            </div>
        )
    }
}

Grid.propTypes = {
    debug: PropTypes.bool,
    style: PropTypes.object,
    className: PropTypes.string,
    hideOn: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.arrayOf(PropTypes.string),
    ]),
    prefix: PropTypes.string,
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]),
}

export default Grid
