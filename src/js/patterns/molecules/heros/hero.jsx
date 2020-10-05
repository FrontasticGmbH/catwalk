//
// Deprecated: This component is deprecated and should not be used any more
//
import deprecate from '@frontastic/common/src/js/helper/deprecate'
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import classnames from 'classnames'

import ComponentInjector from '../../../app/injector'

import MediaImage from '../../../mediaImage'

class MoleculesHero extends Component {
    render () {
        deprecate('The component ' + (this.displayName || this.constructor.name) + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

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
    caption: PropTypes.string,
    className: PropTypes.string,
    size: PropTypes.oneOf(['small', 'normal', 'large']),
    verticalAlign: PropTypes.oneOf(['top', 'center', 'bottom']),
    horizontalAlign: PropTypes.oneOf(['left', 'center', 'right']),
    ratio: PropTypes.string,
}

MoleculesHero.defaultProps = {
    caption: null,
    className: '',
    size: 'normal',
    verticalAlign: 'center',
    horizontalAlign: 'center',
}

export default ComponentInjector.return('MoleculesHero', MoleculesHero)
