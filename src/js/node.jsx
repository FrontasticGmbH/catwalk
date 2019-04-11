import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { Scrollbars } from 'react-custom-scrollbars'
import _ from 'lodash'
import { ConfigurationSchema } from 'frontastic-common'

import Entity from './app/entity'
import Loading from './app/loading'
import MetaData from './node/metaData'

import Page from './page/page'

import configurationResolver from './app/configurationResolver'
import schemas from './schemas'

class Node extends Component {
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

    componentDidUpdate(prevProps) {
        if (this.props.viewKey !== prevProps.viewKey) {
            let scrollTop = this.getScrollPosition(this.props.viewKey)

            if (scrollTop) {
                window.requestAnimationFrame(() => {
                    this.scrollable.scrollTop(scrollTop)
                })
            }
        }
    }

    render () {
        if (!this.props.node.data || !this.props.tastics.isComplete()) {
            return <Loading large entity={this.props.tastics} />
        }

        let nodeData = this.props.node.data || { configuration: {} }

        // Special handling for `path` property, which is always there even though not defined in schema
        nodeData.configuration = _.extend(
            {},
            _.pick(nodeData.configuration, ['path']),
            configurationResolver(
                new ConfigurationSchema(schemas.node.schema, nodeData.configuration),
                (this.props.data.data || {}).stream || {}
            )
        )

        return (<div className='s-node'>
            {this.props.tastics.isComplete() ?
                <Scrollbars
                    autoHide
                    style={{ height: '100vh', width: '100vw' }}
                    ref={(element) => {
                        this.scrollable = element
                    }}
                    onScrollStop={(event) => {
                        this.setScrollPosition(this.props.viewKey, this.scrollable.getScrollTop())
                    }}
                >
                    <MetaData
                        node={nodeData}
                        page={this.props.page.data || {}}
                        data={this.props.data.data || {}}
                    />
                    <Page
                        node={nodeData}
                        page={this.props.page.data || {}}
                        data={this.props.data.data || {}}
                        tastics={this.props.tastics.data}
                    />
                </Scrollbars>
            : null}
            <Loading large entity={this.props.data} />
        </div>)
    }
}

Node.propTypes = {
    viewKey: PropTypes.string.isRequired,
    node: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    tastics: PropTypes.object.isRequired,
}

Node.defaultProps = {
}

export default connect(
    (globalState, props) => {
        let page = null
        if (globalState.node.pages[globalState.node.currentNodeId] &&
             globalState.node.nodeData[globalState.node.currentCacheKey]) {
            page = globalState.node.pages[globalState.node.currentNodeId]
        } else if (globalState.node.last.page) {
            page = new Entity({
                ...globalState.node.last.page.data,
                pageId: 'partial',
                regions: {
                    head: globalState.node.last.page.data.regions.head,
                    main: { regionId: 'main' },
                    footer: { regionId: 'footer' },
                }
            })
        }

        return {
            viewKey: globalState.node.currentCacheKey + '-' + (page && page.data.pageId),
            node: globalState.node.nodes[globalState.node.currentNodeId] ||
                globalState.node.last.node || new Entity(),
            data: globalState.node.nodeData[globalState.node.currentCacheKey] ||
                globalState.node.last.data || new Entity(),
            page: page || new Entity(),
            tastics: globalState.tastic.tastics || new Entity(),
        }
    }
)(Node)
