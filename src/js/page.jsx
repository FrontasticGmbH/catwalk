import React, { Component } from 'react'
import PropTypes from 'prop-types'

import Page from './page/page'

class PageWithoutNode extends Component {
    render () {
        return (<div className='node'>
            <Page page={this.props.page} data={this.props.data} />
        </div>)
    }
}

PageWithoutNode.propTypes = {
    page: PropTypes.object.isRequired,
    data: PropTypes.object.isRequired,
}

PageWithoutNode.defaultProps = {
}

export default PageWithoutNode
