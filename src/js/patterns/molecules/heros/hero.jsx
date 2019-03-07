import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import Image from '../../../../js/image'

class MoleculesHero extends Component {
    render () {
        return (<div className={'c-hero ' + this.props.className}>
            <figure
                className={classnames(
                    'c-hero__figure',
                    'o-ratio',
                    'o-ratio--' + this.props.ratio
                )}>
                <Image
                    className='c-hero__image'
                    media={this.props.media}
                    title={this.props.mediaTitle}
                    cropRatio={this.props.ratio === 'custom' ? null : this.props.ratio} />
                {!this.props.caption ? null : <figcaption className={classnames([
                    'c-hero__caption',
                    'c-hero__caption--' + this.props.size,
                    'c-hero__caption--' + this.props.verticalAlign,
                    'c-hero__caption--' + this.props.horizontalAlign,
                ])}>
                    {this.props.caption}
                </figcaption>}
            </figure>
        </div>)
    }
}

MoleculesHero.propTypes = {
    media: PropTypes.object.isRequired,
    mediaTitle: PropTypes.string,
    caption: PropTypes.string,
    className: PropTypes.string,
    ratio: PropTypes.oneOf(['8:1', '5:1', '3:1', '2:1', '4:3', '16:9', '1:1', 'custom']),
    size: PropTypes.oneOf(['small', 'normal', 'large']),
    verticalAlign: PropTypes.oneOf(['top', 'center', 'bottom']),
    horizontalAlign: PropTypes.oneOf(['left', 'center', 'right']),
}

MoleculesHero.defaultProps = {
    caption: null,
    className: '',
    ratio: 'custom',
    size: 'normal',
    verticalAlign: 'center',
    horizontalAlign: 'center',
}

// These are just default props for the pattern library
MoleculesHero.testProps = {
    media: fixture.image.person,
    caption: fixture.headline.xs,
    ratio: '1:1',
}

export default ComponentInjector.return('MoleculesHero', MoleculesHero)
