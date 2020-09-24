import React, { Component, Fragment } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import { ConfigurationSchema } from 'frontastic-common'

import emptyEntity from './helper/emptyEntity'
import Loading from './app/loading'
import MetaData from './node/metaData'
import Markdown from './component/markdown'

import Page from './page/page'

import configurationResolver from './app/configurationResolver'
import schemas from './schemas'
import pageSelector from './helper/pageSelector'

import Notifications from '../../../themes/frontastic/boost/src/js/patterns/molecules/Notifications/Default.jsx'

class Node extends Component {
    render () {
        if (!this.props.node.data || !this.props.tastics.isComplete()) {
            return <Loading large entity={this.props.tastics} />
        }
        let nodeData = this.props.node.data || { configuration: {} }

        // Special handling for `path` property, which is always there even though not defined in schema
        nodeData.configuration = {
            path: nodeData.configuration.path || null,
            ...configurationResolver(
                new ConfigurationSchema(schemas.node.schema, nodeData.configuration),
                (this.props.data.data || {}).stream || {}
            ),
        }

        // the custom (css) classname can be added in backstage
        // in the Display Properties of each Node Configuration (gear icon)
        let customClassname = nodeData.configuration.displayClassname || ''

        return (
            <div className={`s-node s-node--${nodeData.nodeType} ${customClassname}`}>
                {this.props.tastics.isComplete() && this.props.page.data && this.props.data.data ? (
                    <Fragment>
                        <MetaData node={nodeData} page={this.props.page.data} data={this.props.data.data} />
                        {this.props.node.data.error && <Markdown text={this.props.node.data.error} />}
                        <Page
                            node={nodeData}
                            page={this.props.page.data}
                            data={this.props.data.data}
                            tastics={this.props.tastics.data}
                        />
                        <Notifications />
                    </Fragment>
                ) : null}
                <Loading large entity={this.props.data} />
            </div>
        )
    }
}

Node.propTypes = {
    node: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    tastics: PropTypes.object.isRequired,
}

Node.defaultProps = {}

export default connect((globalState, props) => {
    const page = pageSelector(globalState)

    return {
        node: globalState.node.nodes[globalState.node.currentNodeId] || globalState.node.last.node || emptyEntity,
        data: globalState.node.nodeData[globalState.node.currentCacheKey] || globalState.node.last.data || emptyEntity,
        page: page || emptyEntity,
        tastics: globalState.tastic.tastics || emptyEntity,
    }
})(Node)
