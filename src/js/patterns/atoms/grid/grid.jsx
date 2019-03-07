import * as React from 'react'

export default class Grid extends React.Component {
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
            windowWidth: window ? document.body.offsetWidth : -1,
            gridWidth: this._gridRef.current ? this._gridRef.current.getBoundingClientRect().width : -1,
        })
    }

    componentWillMount() {
        this.handleResize()
    }
    componentDidMount() {
        window.addEventListener('resize', this.handleResize)
        this.handleResize()
    }
    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize)
    }

    render() {
        const { debug, style, className, hideOn, prefix } = this.props
        const { windowWidth, gridWidth } = this.state

        // exact same code as in Cell.jsx. Maybe time for a helper?
        let hideOnClasses = ''
        if (!Array.isArray(hideOn)) {
            hideOnClasses = ` ${prefix}--hidden-${hideOn}`
        } else {
            hideOnClasses = hideOn.reduce((acc, crnt) => acc + ` ${prefix}--hidden-${crnt}`, '')
        }
        const children = React.Children.map(this.props.children, (c) => {
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
