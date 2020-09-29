//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import RssParser from 'rss-parser'
import app from '../../app/app'

class RssFeed extends Component {
    constructor (props) {
        super(props)

        this.state = {
            feed: null,
        }
    }

    componentDidMount = () => {
        if (this.state.feed) {
            return
        }

        if (!this.props.tastic.schema.get('feed-url')) {
            // eslint-disable-next-line no-console
            console.warn('Missing Feed URL')
            return
        }

        const parser = new RssParser()
        parser.parseURL(
            app.getRouter().path(
                'Frontastic.Frontend.Proxy',
                {
                    url: this.props.tastic.schema.get('feed-url'),
                }
            )
        ).then((feed) => {
            this.setState({
                feed: feed,
            })
        })
    }

    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        if (!this.state.feed) {
            return null
        }

        return (<div className='c-tableview u-margin-small'>
            {_.map(this.state.feed.items, (item) => {
                return (<div className='c-tableview__cell' key={item.guid}>{item.title}</div>)
            })}
        </div>)
    }
}

RssFeed.propTypes = {
    tastic: PropTypes.object.isRequired,
}

RssFeed.defaultProps = {}

export default RssFeed
