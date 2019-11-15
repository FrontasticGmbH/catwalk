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
            tastics: props.tastics,
        }
    }

    static getDerivedStateFromProps (nextProps, prevState) {
        if ((prevState.page.pageId !== nextProps.page.pageId) ||
        (prevState.tastics.length !== nextProps.tastics.length)) {
            return {
                page: new Page(nextProps.page || {}, _.keys(nextProps.page.regions), nextProps.tastics || []),
                tastics: nextProps.tastics,
            }
        }

        return null
    }

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
