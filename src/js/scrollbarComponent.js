import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { Scrollbars } from 'react-custom-scrollbars'

import { ScrollContext } from './app/scrollContext'
import pageSelector from './helper/pageSelector'

/**
 * Provides frontastic specific scrollbar behaviour.
 *
 * It leverages react-custom-scrollbars.
 *
 * I also takes care that, if you go back in your browser history,
 * you go back to the same scroll position as before.
 *
 * If you want to disable it,
 * you can overwrite the `<AppContainer />` component using ComponentInjector.
 */
class ScrollbarComponent extends Component {
    scrollable = null

    scrollPositions = new Map()

    getScrollPosition (key) {
        if (!key) {
            return null
        }

        return this.scrollPositions.get(key) || null
    }

    setScrollPosition (key, scrollPosition) {
        if (!key) {
            return
        }
        return this.scrollPositions.set(key, scrollPosition)
    }

    /**
     * Discards any remembered scroll psoition for the current page
     */
    forceScrollToTop = (key) => {
        this.setScrollPosition(key, 0)
        window.requestAnimationFrame(() => {
            this.scrollable.scrollTop(0)
        })
    }

    componentDidUpdate (prevProps) {
        if (this.scrollable && (this.props.viewKey !== prevProps.viewKey)) {
            let scrollTop = this.getScrollPosition(this.props.viewKey) || 0

            window.requestAnimationFrame(() => {
                this.scrollable.scrollTop(scrollTop)
            })
        }
    }

    render () {
        return (
            <Scrollbars
                autoHide
                style={{ height: '100vh', width: '100vw' }}
                id='scroll-container'
                ref={(element) => {
                        this.scrollable = element
                    }}
                onScrollStop={(event) => {
                        this.setScrollPosition(this.props.viewKey, this.scrollable.getScrollTop())
                    }}
                >
                <ScrollContext.Provider value={{ forceScrollToTop: this.forceScrollToTop }}>
                    {this.props.children}
                </ScrollContext.Provider>
            </Scrollbars>
        )
    }
}

ScrollbarComponent.propTypes = {
    viewKey: PropTypes.string.isRequired,
    children: PropTypes.node,
}

ScrollbarComponent.defaultProps = {
}

export default connect(
    (globalState, props) => {
        const page = pageSelector(globalState)

        return {
            viewKey: globalState.node.currentCacheKey + '-' + (page && page.data && page.data.pageId),
        }
    }
)(ScrollbarComponent)
