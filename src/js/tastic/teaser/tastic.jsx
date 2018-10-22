import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import _ from 'lodash'

import app from '../../app/app'
import Entity from '../../app/entity'
import Markdown from '../../component/markdown'

import Image from '../../image'

class TeaserTastic extends Component {
    componentDidMount () {
        // @TODO: This is supposed to be already fetched on server side.
        let teaser = this.props.tastic.schema.get('teaser')
        if (teaser) {
            app.getLoader('data').get({ app: 'teaser', dataId: teaser })
        }
    }

    optionsFromSettings (imageSettings = {}) {
        return _.omit(
            imageSettings,
            ['media', 'ratio', 'width', 'height']
        )
    }

    render () {
        if (!this.props.teaser.isComplete()) {
            return null
        }

        const teaser = this.props.teaser.data
        return (<div className='o-layout'>
            <div className='o-layout__item u-1/2@lap' style={{ marginBottom: '1.5em' }}>
                {teaser.image1 ? <Image
                    media={teaser.image1.media || {}}
                    cropRatio={teaser.image1.ratio || '3:1'}
                    options={this.optionsFromSettings(teaser.image1)}
                /> : null}
            </div>
            <div className='o-layout__item u-1/2@lap'>
                <Markdown text={teaser.text2} />
            </div>
            <div className='o-layout__item u-1/2@lap u-hide-until@lap'>
                <Markdown text={teaser.text3} />
            </div>
            <div className='o-layout__item u-1/2@lap' style={{ marginBottom: '1.5em' }}>
                {teaser.image4 ? <Image
                    media={teaser.image4.media || {}}
                    cropRatio={teaser.image4.ratio || '3:1'}
                    options={this.optionsFromSettings(teaser.image4)}
                /> : null}
            </div>
            <div className='o-layout__item u-1/2@lap u-hide-from@lap'>
                <Markdown text={teaser.text3} />
            </div>
        </div>)
    }
}

TeaserTastic.propTypes = {
    teaser: PropTypes.object.isRequired,
    tastic: PropTypes.object.isRequired,
}

TeaserTastic.defaultProps = {
}

export default connect(
    (globalState, props) => {
        let teaser = null
        if (props.tastic.configuration.teaser) {
            teaser = globalState.data.data[props.tastic.configuration.teaser] || null
        }

        return {
            teaser: teaser || new Entity(),
            ...props,
        }
    }
)(TeaserTastic)
