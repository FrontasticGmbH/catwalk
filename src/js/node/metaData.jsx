import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import Title from './title'
import Description from './description'
import Keywords from './keywords'

class MetaData extends Component {
    render () {
        return (<Fragment>
            <Title node={this.props.node} context={this.props.context} />
            <Description node={this.props.node} context={this.props.context} />
            <Keywords node={this.props.node} context={this.props.context} />
        </Fragment>)
    }
}

MetaData.propTypes = {
    node: PropTypes.object.isRequired,
    context: PropTypes.object.isRequired,
}

MetaData.defaultProps = {}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            context: globalState.app.context,
        }
    }
)(MetaData)
