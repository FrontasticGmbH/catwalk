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
        // eslint-disable-next-line no-console
        console.warn('Component error', error)
        this.setState({ error: error })
    }

    render () {
        if (this.state.error) {
            if (!this.props.isDebug) {
                // in production we don't want to render the error message
                return null
            }

            return (<div className='tastic e-tastic--errored'>
                <p>{this.state.error.toString()}</p>
            </div>)
        }

        return this.props.children
    }
}

ErrorBoundary.propTypes = {
    children: PropTypes.any,
    isDebug: PropTypes.bool.isRequired,
}

ErrorBoundary.defaultProps = {
}

export default ErrorBoundary
