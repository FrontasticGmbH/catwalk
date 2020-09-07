import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

import Notifier from '@frontastic/common/src/js/notifier'
import app from './app/app'

import Page from './page/page'
import emptyEntity from './helper/emptyEntity'

class Preview extends Component {
    constructor (props) {
        super(props)

        this.state = {
            highlight: null,
        }
    }

    componentDidMount () {
        this.connectWebSocket()

        window.addEventListener('message', this.highlight.bind(this), false)
    }

    componentDidUpdate (prevProps) {
        if (prevProps.context.environment !== this.props.context.environment) {
            this.webSocket.close()
            this.webSocket = null
            this.connectWebSocket()
        }
    }

    componentWillUnmount () {
        window.removeEventListener('message', this.highlight.bind(this), false)
    }

    highlight (event) {
        // @TODO: Verify event origin
        if (!event.isTrusted) {
            return
        }

        this.setState({ highlight: event.data.item })
    }

    connectWebSocket () {
        if (!this.props || !this.props.previewId || this.notifier) {
            // If called when page is closing, web socket disconnects, but the
            // component does not exist any more
            return
        }

        this.notifier = new Notifier(
            {
                isDebug: (this.props.context.environment === 'dev'),
                previewId: this.props.previewId,
            },
            {
                Refresh: () => {
                    app.getLoader('node').reloadPreview({ preview: this.props.previewId })
                },
                Select: (payload) => {
                    this.setState({ highlight: payload.item })
                },
                Highlight: (payload) => {
                    this.setState({ highlight: payload.item })
                },
                EndHighlight: () => {
                    this.setState({ highlight: null })
                },
            }
        )
    }

    notifier = null

    render () {
        if (!this.props.tastics.isComplete() ||
            !this.props.node ||
            !this.props.page) {
            return (<div style={{ display: 'table-cell', verticalAlign: 'middle', textAlign: 'center', height: '100vh', width: '100vw' }}>
                <h1 style={{ color: '#463460', fontFamily: 'sans-serif' }}>
                    Initializing Preview
                </h1>
            </div>)
        }

        return (<div className='s-preview'>
            <Page
                node={this.props.node.data || {}}
                page={this.props.page.data || {}}
                data={this.props.data.data || {}}
                highlight={this.state.highlight}
                tastics={this.props.tastics.data}
            />
        </div>)
    }
}

Preview.propTypes = {
    previewId: PropTypes.string.isRequired,
    node: PropTypes.object,
    data: PropTypes.object.isRequired,
    page: PropTypes.object,
    tastics: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

Preview.defaultProps = {
}

export default connect(
    (globalState, props) => {
        let previewId = globalState.app.route.get('preview')
        return {
            previewId: previewId,
            node: globalState.node.nodes[globalState.node.previewId] ||
                globalState.node.last.node,
            data: globalState.node.nodeData[globalState.node.currentCacheKey] ||
                globalState.node.last.data,
            page: globalState.node.pages[globalState.node.previewId] ||
                globalState.node.last.page,
            tastics: globalState.tastic.tastics || emptyEntity,
            context: globalState.app.context,
        }
    }
)(Preview)
