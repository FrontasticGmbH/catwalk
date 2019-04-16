import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { ScrollContext } from './scrollContext'

/**
 * Component with a render prop that helps you with scrolling-related stuff
 *
 * @example
 * <WithScrollHelper
 *   render={({forceScrollToTop}) => {
 *     return <button onClick={forceScrollToTop}>Scroll to top!</button>
 *   }}
 * />
 */
class WithScrollHelper extends Component {
    static contextType = ScrollContext
    render () {
        return this.props.render({
            forceScrollToTop: this.context.forceScrollToTop,
        })
    }
}

WithScrollHelper.propTypes = {
    render: PropTypes.func.isRequired,
}

export default WithScrollHelper
