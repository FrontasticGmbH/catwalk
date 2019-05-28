import React, { Component } from 'react'
import PropTypes from 'prop-types'

import ComponentInjector from '../app/injector'

import zoomOut from '../../icons/zoom_out.svg'
import zoomIn from '../../icons/zoom_in.svg'
import reset from '../../icons/all_out.svg'

const MIN_SCALE = 1
const MAX_SCALE = 4
const SETTLE_RANGE = 0.001
const ADDITIONAL_LIMIT = 0.2
const DOUBLE_TAP_THRESHOLD = 300
const ANIMATION_SPEED = 0.04
const RESET_ANIMATION_SPEED = 0.16
const INITIAL_X = 0
const INITIAL_Y = 0
const INITIAL_SCALE = 1

const settle = (val, target, range) => {
    const lowerRange = val > target - range && val < target
    const upperRange = val < target + range && val > target
    return lowerRange || upperRange ? target : val
}

const inverse = (x) => { return x * -1 }

const getPointFromEvent = (touch, element) => {
    const rect = element.getBoundingClientRect()
    return {
        x: touch.clientX - rect.left,
        y: touch.clientY - rect.top,
    }
}

const getMidpoint = (pointA, pointB) => {
    return {
        x: (pointA.x + pointB.x) / 2,
        y: (pointA.y + pointB.y) / 2,
    }
}

const getDistanceBetweenPoints = (pointA, pointB) => {
    return Math.sqrt(Math.pow(pointA.y - pointB.y, 2) + Math.pow(pointA.x - pointB.x, 2))
}

const between = (min, max, value) => {
    return Math.min(max, Math.max(min, value))
}

const checkBetween = (m1, m2, value) => {
    return m1 < m2 ? between(m1, m2, value) : between(m2, m1, value)
}

class Zoom extends Component {
    constructor (props) {
        super(props)

        this.state = {
            x: INITIAL_X,
            y: INITIAL_Y,
            scale: INITIAL_SCALE,
            width: props.width,
            height: props.height,
        }
    }

    UNSAFE_componentWillReceiveProps = (props) => { // eslint-disable-line camelcase
        this.setState({
            x: INITIAL_X,
            y: INITIAL_Y,
            scale: INITIAL_SCALE,
            width: props.width,
            height: props.height,
        })
    }

    zoomTo = (scale, midpoint) => {
        const frame = () => {
            if (this.state.scale === scale) {
                return null
            }

            const distance = scale - this.state.scale
            const targetScale = this.state.scale + (ANIMATION_SPEED * distance)

            this.zoom(settle(targetScale, scale, SETTLE_RANGE), midpoint)
            this.animation = requestAnimationFrame(frame)
        }

        this.animation = requestAnimationFrame(frame)
    }

    reset = () => {
        const frame = () => {
            if (this.state.scale === INITIAL_SCALE && this.state.x === INITIAL_X && this.state.y === INITIAL_Y) {
                return null
            }

            const distance = INITIAL_SCALE - this.state.scale
            const distanceX = INITIAL_X - this.state.x
            const distanceY = INITIAL_Y - this.state.y

            const targetScale = settle(this.state.scale + (RESET_ANIMATION_SPEED * distance), INITIAL_SCALE, SETTLE_RANGE)
            const targetX = settle(this.state.x + (RESET_ANIMATION_SPEED * distanceX), INITIAL_X, SETTLE_RANGE)
            const targetY = settle(this.state.y + (RESET_ANIMATION_SPEED * distanceY), INITIAL_Y, SETTLE_RANGE)

            const nextWidth = this.props.width * targetScale
            const nextHeight = this.props.height * targetScale

            this.setState({
                x: targetX,
                y: targetY,
                scale: targetScale,
                width: nextWidth,
                height: nextHeight,
            }, () => {
                this.animation = requestAnimationFrame(frame)
            })
        }

        this.animation = requestAnimationFrame(frame)
    }

    handleTouchStart = (event) => {
        this.animation && cancelAnimationFrame(this.animation)
        if (event.touches.length === 2) {
            this.handlePinchStart(event)
        }
        if (event.touches.length === 1) {
            this.handleTapStart(event)
        }
    }

    handleTouchMove = (event) => {
        if (event.touches.length === 2) {
            this.handlePinchMove(event)
        }
        if (event.touches.length === 1) {
            this.handlePanMove(event)
        }
    }

    handleTouchEnd = (event) => {
        if (event.touches.length > 0) {
            return null
        }

        if (this.state.scale > MAX_SCALE) {
            return this.zoomTo(MAX_SCALE, this.lastMidpoint)
        }
        if (this.state.scale < MIN_SCALE) {
            return this.zoomTo(MIN_SCALE, this.lastMidpoint)
        }

        if (this.lastTouchEnd && this.lastTouchEnd + DOUBLE_TAP_THRESHOLD > event.timeStamp) {
            this.reset()
        }

        this.lastTouchEnd = event.timeStamp
    }

    handleTapStart = (event) => {
        this.lastPanPoint = getPointFromEvent(event.touches[0], this.container)
    }

    handlePanMove = (event) => {
        if (this.state.scale === 1) {
            return null
        }

        event.preventDefault()

        const point = getPointFromEvent(event.touches[0], this.container)
        const nextX = this.state.x + point.x - this.lastPanPoint.x
        const nextY = this.state.y + point.y - this.lastPanPoint.y

        this.setState({
            x: between(this.props.width - this.state.width, 0, nextX),
            y: between(this.props.height - this.state.height, 0, nextY),
        })

        this.lastPanPoint = point
    }

    handlePinchStart = (event) => {
        const pointA = getPointFromEvent(event.touches[0], this.container)
        const pointB = getPointFromEvent(event.touches[1], this.container)
        this.lastDistance = getDistanceBetweenPoints(pointA, pointB)
    }

    handlePinchMove = (event) => {
        event.preventDefault()
        const pointA = getPointFromEvent(event.touches[0], this.container)
        const pointB = getPointFromEvent(event.touches[1], this.container)
        const distance = getDistanceBetweenPoints(pointA, pointB)
        const midpoint = getMidpoint(pointA, pointB)
        const scale = between(MIN_SCALE - ADDITIONAL_LIMIT, MAX_SCALE + ADDITIONAL_LIMIT, this.state.scale * (distance / this.lastDistance))

        this.zoom(scale, midpoint)

        this.lastMidpoint = midpoint
        this.lastDistance = distance
    }

    handleWheel = (event) => {
        event.preventDefault()
        event.stopPropagation()

        const midpoint = getPointFromEvent(event, this.container)
        const scale = between(MIN_SCALE - ADDITIONAL_LIMIT, MAX_SCALE + ADDITIONAL_LIMIT, this.state.scale * (1 - event.deltaY / 100))
        this.zoom(scale, midpoint)

        this.lastMidpoint = midpoint
        this.lastDistance = 0
    }

    handleClick = (zoomIn = true) => {
        let midpoint = {
            x: this.props.width / 2,
            y: this.props.height / 2,
        }
        this.zoom(this.state.scale * (zoomIn ? 1.5 : 0.75), midpoint)

        this.lastMidpoint = midpoint
        this.lastDistance = 0
    }

    zoom = (scale, midpoint) => {
        const nextWidth = this.props.width * scale
        const nextHeight = this.props.height * scale
        const nextX = this.state.x + (inverse(midpoint.x * scale) * (nextWidth - this.state.width) / nextWidth)
        const nextY = this.state.y + (inverse(midpoint.y * scale) * (nextHeight - this.state.height) / nextHeight)

        this.setState({
            width: nextWidth,
            height: nextHeight,
            x: checkBetween(0, this.props.width - nextWidth, nextX),
            y: checkBetween(0, this.props.height - nextHeight, nextY),
            scale,
        })
    }

    render () {
        return (<div
            ref={(ref) => { this.container = ref }}
            onTouchStart={this.handleTouchStart}
            onTouchMove={this.handleTouchMove}
            onTouchEnd={this.handleTouchEnd}
            onWheel={this.handleWheel}
            style={{
                overflow: 'hidden',
                width: this.props.width,
                height: this.props.height,
            }}
            >
            {this.props.children(this.state.x, this.state.y, this.state.scale)}
            <div className='c-button-overlay'>
                <button
                    onClick={() => { this.handleClick(true) }}
                    className='c-button u-margin-bottom-tiny c-button--small'
                >
                    <img src={zoomIn} width='24' height='24' alt='Zoom In' />
                </button>
                <button
                    onClick={() => { this.handleClick(false) }}
                    className='c-button u-margin-bottom-tiny c-button--small'
                >
                    <img src={zoomOut} width='24' height='24' alt='Zoom Out' />
                </button>
                <button
                    className='c-button u-margin-bottom-tiny c-button--small'
                    onClick={() => { this.reset() }}
                >
                    <img src={reset} width='24' height='24' alt='Reset Zoom' />
                </button>
            </div>
        </div>)
    }
}

Zoom.propTypes = {
    children: PropTypes.func.isRequired,
    width: PropTypes.number.isRequired,
    height: PropTypes.number.isRequired,
}

export default ComponentInjector.return('Zoom', Zoom)
