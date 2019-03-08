import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import ComponentInjector from '../../../app/injector'

import fixture from '../../fixture'

import MediaImage from '../../../mediaImage'

class MoleculesHero extends Component {
    render () {
        return (<div className={'c-hero ' + this.props.className}>
            <figure
                className={classnames(
                    'c-hero__figure',
                    'o-ratio',
                    'o-ratio--' + this.props.ratio
                )}>
                <MediaImage className='c-hero__image' media={this.props.media} />
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
    size: PropTypes.oneOf(['small', 'normal', 'large']),
    verticalAlign: PropTypes.oneOf(['top', 'center', 'bottom']),
    horizontalAlign: PropTypes.oneOf(['left', 'center', 'right']),
}

MoleculesHero.defaultProps = {
    caption: null,
    className: '',
    size: 'normal',
    verticalAlign: 'center',
    horizontalAlign: 'center',
}

// These are just default props for the pattern library
MoleculesHero.testProps = {
    media: {
        media: fixture.image.person,
        title: fixture.headline.short,
        ratio: '1:1',
    },
    caption: fixture.headline.xs,
}

export default ComponentInjector.return('MoleculesHero', MoleculesHero)
