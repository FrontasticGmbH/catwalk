import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Markdown from '../../component/markdown'

class ContentTastic extends Component {
    render () {
        let content = this.props.rawData.stream[this.props.tastic.configuration.stream]
        if (!content) {
            return null
        }

        return (<div className='s-text'>
            {_.map(content.attributes, (attribute) => {
                let className = `e-content e-content__${attribute.type} e-content__${attribute.type}--${attribute.attributeId}`

                switch (attribute.type) {
                case 'Symbol':
                    return <h1 className={className} key={attribute.attributeId}>{attribute.content}</h1>
                case 'Text':
                    return (<div className={className} key={attribute.attributeId}>
                        <Markdown text={attribute.content} />
                    </div>)
                default:
                    return <div className={className} key={attribute.attributeId}>{attribute.content}</div>
                }
            })}
        </div>)
    }
}

ContentTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

ContentTastic.defaultProps = {
}

export default ContentTastic
