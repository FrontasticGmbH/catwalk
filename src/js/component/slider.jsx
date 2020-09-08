import React, { Component } from 'react'
import classnames from 'classnames'
import PropTypes from 'prop-types'

import ComponentInjector from '../app/injector'

import debounce from '@frontastic/common/src/js/helper/debounce'

// import Flickity from 'react-flickity-component' // Imported dynamically in constructor

const defaultOptions = {
    cellAlign: 'left',
    pageDots: true,
    freeScroll: false,
    dragThreshold: 20,
    prevNextButtons: true,
    groupCells: true,
    draggable: '>1',
    arrowShape:
        'M44.2,45.1l31.9-31.9c3-3,3-7.7,0-10.6l-0.4-0.4c-3-3-7.7-3-10.6,0L22.6,44.7 c-3,3-3,7.7,0,10.6l42.5,42.5c3,3,7.7,3,10.6,0l0,0c3-3,3-7.7,0-10.6L44.2,55.7C41.3,52.8,41.3,48,44.2,45.1z',
}

class Slider extends Component {
    constructor (props) {
        super(props)

        if (typeof window !== 'undefined' && window && window.document) {
            this.Flickity = require('react-flickity-component')
            // we need to import the flickity-as-nav-for plugin in order to make asNavFor feature (see below) work.
            require('flickity-as-nav-for')
        }
    }

    Flickity = null

    resizeTimer = null

    updateDimensions = () => {
        this.carousel.slider.classList.add('slider-is-resizing')

        clearTimeout(this.resizeTimer)

        this.carousel.resize()
        this.resizeTimer = setTimeout(() => {
            this.carousel.slider.classList.remove('slider-is-resizing')
            this.carousel.resize()
            this.carousel.reloadCells()
        }, 300)
    }

    debouncedResize = debounce(() => {
        if (!this.carousel) {
            return
        }

        this.carousel.resize()
        this.carousel.reloadCells()
    }, 250)

    checkSliderAlone = () => {
        if (this.carousel && this.carousel.slides) {
            this.carousel.slides.length < 2
                ? this.carousel.element.classList.add('slider-is-alone')
                : this.carousel.element.classList.remove('slider-is-alone')
        }
    }

    componentDidUpdate = () => {
        this.debouncedResize()
    }

    componentDidMount = () => {
        // @TODO: How to render Flickity on SSR?
        if (!window) {
            return null
        }

        window.addEventListener('resize', () => {
            this.updateDimensions()
            this.checkSliderAlone()
        })
        this.forceUpdate()

        this.carousel.on('ready', this.checkSliderAlone)
    }

    componentWillUnmount () {
        // @TODO: How to render Flickity on SSR?
        if (!window) {
            return null
        }

        window.removeEventListener('resize', this.updateDimensions)
    }

    render () {
        // @TODO: How to render Flickity on SSR?
        if (!window || !this.Flickity) {
            return null
        }

        const { options, className, children, slidesPerPage } = this.props
        const Flickity = this.Flickity

        return (
            <Flickity
                className={classnames('c-slider', slidesPerPage ? `c-slider--${slidesPerPage}` : '', className)}
                options={{ ...defaultOptions, ...options }}
                reloadOnUpdate
                flickityRef={(carousel) => {
                    return (this.carousel = carousel)
                }}
                >
                {children}
            </Flickity>
        )
    }
}

Slider.propTypes = {
    options: PropTypes.object,
    className: PropTypes.string,
    slidesPerPage: PropTypes.oneOf([1, 2, 3, 4]),
    children: PropTypes.array,
}

Slider.defaultProps = {
    slidesPerPage: 1,
    defaultOptions,
}

export default ComponentInjector.return('Slider', Slider)
