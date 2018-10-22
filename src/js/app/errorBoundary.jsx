import React, { Component } from 'react'
import PropTypes from 'prop-types'

class ErrorBoundary extends Component {
    constructor (props) {
        super(props)

        this.state = {
            error: null,
        }
    }

    componentDidCatch (error, info) {
        console.warn('Component error', error)
        this.setState({ error: error })
    }

    render () {
        if (this.state.error) {
            return (<div className='tastic e-tastic--errored'>
                <p>{this.state.error.toString()}</p>
            </div>)
        }

        return this.props.children
    }
}

ErrorBoundary.propTypes = {
    children: PropTypes.any,
}

ErrorBoundary.defaultProps = {
}

export default ErrorBoundary
