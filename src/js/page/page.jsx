import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import DefaultLayout from './layout'
import Page from 'frontastic-common/src/js/domain/page'

class PageView extends Component {
    constructor (props) {
        super(props)

        this.layouts = {
            // @TODO: Add more complex layouts here
        }

        this.state = {
            page: new Page(props.page || {}, _.keys(props.page.regions), props.tastics || []),
            tastics: this.props.tastics
        }
    }

    static getDerivedStateFromProps(nextProps, prevState) {
        if ((prevState.page.pageId !== nextProps.page.pageId) ||
        (prevState.tastics.length !== nextProps.tastics.length)) {
            return {
                page: new Page(nextProps.page || {}, _.keys(nextProps.page.regions), nextProps.tastics || []),
            }
        }
        return null;
    }

    /* UNSAFE_componentWillUpdate (nextProps) { // eslint-disable-line camelcase
        // Only re-create the page when its ID changes (thus a different page
        // should be shown). The page ID will change for every preview page
        // update.
        
        if ((this.props.page.pageId !== nextProps.page.pageId) ||
            (this.props.tastics.length !== nextProps.tastics.length)) {
            this.setState({
                page: new Page(nextProps.page || {}, _.keys(nextProps.page.regions), nextProps.tastics || []),
            })
        }
    } */

    getLayout () {
        if (!this.state.page.layoutId) {
            return DefaultLayout
        }

        return this.layouts[this.state.page.layoutId] || DefaultLayout
    }

    render () {
        let Layout = this.getLayout()
        return <Layout
            node={this.props.node}
            page={this.state.page}
            data={!_.isArray(this.props.data) ? this.props.data : {}}
            highlight={this.props.highlight} />
    }
}

PageView.propTypes = {
    node: PropTypes.object.isRequired,
    page: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
    tastics: PropTypes.array.isRequired,
    highlight: PropTypes.any,
}

PageView.defaultProps = {
    highlight: null,
}

export default PageView
