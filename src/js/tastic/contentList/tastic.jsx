//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

class ContentListTastic extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        let contentList = this.props.rawData.stream[this.props.tastic.configuration.stream]
        if (!contentList) {
            return null
        }

        return (<div>
            <p><small>Showing {contentList.count} results from {contentList.total}</small></p>
            <ul>
                {_.map(contentList.items, (content) => {
                    return <li key={content.contentId}>
                        <a href='/'>
                            <strong>{content.name}</strong>
                            {_.map(content.attributes, (attribute) => {
                                if (attribute.type !== 'Text') {
                                    return null
                                }

                                return (<p key={attribute.attributeId}>
                                    {attribute.content.substr(0, 200)}…
                                </p>)
                            })}
                        </a>
                    </li>
                })}
            </ul>
        </div>)
    }
}

ContentListTastic.propTypes = {
    rawData: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

ContentListTastic.defaultProps = {
}

export default ContentListTastic
