import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

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
        if (!this.props) {
            // If called when page is closing, web socket disconnects, but the
            // component does not exist any more
            return
        }

        let socketUrl = ((this.props.context.environment === 'dev') ?
            'ws://demo.frontastic.io.local:8080' :
            'wss://demo.frontastic.io:8080') +
            '/ws?preview=' + this.props.previewId

        this.webSocket = new WebSocket(socketUrl)
        this.webSocket.onmessage = (event) => {
            let message = JSON.parse(event.data)
            switch (message.Name) {
            case 'Refresh':
                app.getLoader('node').reloadPreview({ preview: this.props.previewId })
                break
            case 'Highlight':
                this.setState({ highlight: message.Payload.item })
                break
            case 'EndHighlight':
                this.setState({ highlight: null })
                break
            case 'Ping':
                // Ignore
                break
            default:
                // eslint-disable-next-line no-console
                console.info(message)
                // Do nothing for other messages
            }
        }
        this.webSocket.onclose = this.connectWebSocket
    }

    webSocket = null

    render () {
        if (!this.props.tastics.isComplete() ||
            !this.props.node ||
            !this.props.page) {
            return <h1>Initializing Preview</h1>
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
