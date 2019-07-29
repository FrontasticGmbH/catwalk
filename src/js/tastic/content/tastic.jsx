import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Markdown from '../../component/markdown'
import { FormattedDate, FormattedTime } from 'react-intl'

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
                case 'DateTime':
                    const date = new Date(attribute.content)
                    return (<div className={className} key={attribute.attributeId}>
                        <FormattedDate value={date} /> <FormattedTime value={date} />
                    </div>)
                case 'LIST':
                    if (!_.isArray(attribute.content)) {
                        console.warn('LIST content provided but content is not an array, ignoring.')
                        return null
                    }

                    const contentList = attribute.content.map(listItem => {
                        return <li>{listItem.id}</li>
                    })
                    return (<div className={className} key={attribute.attributeId}>
                        {contentList.length ? <ul>{contentList}</ul> : null}
                    </div>)
                case 'Asset':
                    if (!attribute.content) {
                        return null
                    }

                    return (<div className={className} key={attribute.attributeId}>
                        {attribute.content.handle}
                    </div>)
                case 'Status':
                case 'ID':
                    // do not display the status of this content here
                    return null
                default:
                    let content = attribute.content
                    if (!React.isValidElement(content)) {
                        console.warn('Retrieved not renderable content: "' + content + '". Skipping it.')
                        return null
                    }
                    return <div className={className} key={attribute.attributeId}>{content}</div>
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
