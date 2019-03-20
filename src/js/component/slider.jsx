import React, { Component } from 'react'
import PropTypes from 'prop-types'
// import Flickity from 'flickity/src/flickity'
// import imagesLoaded from 'imagesloaded'
import _ from 'lodash'

import ComponentInjector from '../app/injector'

class Slider extends Component {
    constructor (props) {
        super(props)

        this.state = {
            selected: 0,
            error: null,
        }
    }

    componentDidMount = () => {
        this.flickity = new Flickity(this.refs.carousel, _.extend(
            {
                cellSelector: '.c-slider__item',
                cellAlign: 'left',
                pageDots: false,
                prevNextButtons: false,
                draggable: '>1',
            },
            this.props.options
        ))

        this.flickity.on('cellSelect', this.updateSelected)

        if (this.props.imagesLoaded) {
            imagesLoaded(this.refs.carousel, () => {
                this.flickity.resize()
            })
        }
    }

    componentDidUpdate = (nextProps) => {
        if (_.isEqual(nextProps.children, this.props.children)) {
            return
        }

        this.flickity.reloadCells()
        if (this.props.imagesLoaded) {
            imagesLoaded(this.refs.carousel, () => {
                this.flickity.resize()
            })
        }
    }

    componentWillUnmount = () => {
        if (this.flickity) {
            this.flickity.off('cellSelect', this.updateSelected)
            this.flickity.destroy()
        }
    }

    updateSelected = () => {
        this.setState({
            selected: this.flickity.selectedIndex,
        })
    }

    render () {
        // @TODO: How to render Flickity on SSR?
        if (!window || !Flickity) {
            return null
        }

        return (<div className={this.props.className} ref='carousel'>
            <div className='flickity-viewport'>
                <div className='flickity-slider'>
                    {this.props.children}
                </div>
            </div>
        </div>)
    }
}

Slider.propTypes = {
    imagesLoaded: PropTypes.bool,
    options: PropTypes.object,
    className: PropTypes.string,
    children: PropTypes.array,
}

Slider.defaultProps = {
    imagesLoaded: true,
    options: {},
    className: 'c-slider js-slider',
}

export default ComponentInjector.return('Slider', Slider)
