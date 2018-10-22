import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import classnames from 'classnames'
import _ from 'lodash'

import app from './app/app'

import patterns from './patternLibrary/patternList'
import { displayName, isReactComponent } from './patternLibrary/functions'

class PatternLibrary extends Component {
    constructor (props) {
        super(props)

        this.state = {
            category: null,
            size: 'hand',
            width: 375,
            highlight: null,
        }
    }

    selectPattern (pattern) {
        app.getRouter().replace('Frontastic.Frontend.PatternLibrary.overview', { pattern: pattern })
        this.setState({ category: null })
    }

    handleStart = (event) => {
        document.addEventListener('mousemove', this.handleDrag)
        document.addEventListener('mouseup', this.handleEnd)
    }

    handleDrag = (event) => {
        event.stopPropagation()

        this.setState({ width: Math.max(360, this.position(event)) })
    }

    handleEnd = (event) => {
        document.removeEventListener('mousemove', this.handleDrag)
        document.removeEventListener('mouseup', this.handleEnd)
    }

    handleKeyDown = (event) => {
        event.preventDefault()

        switch (event.keyCode) {
        case 38:
        case 39:
            this.setState({ width: this.state.width + 1 })
            break
        case 37:
        case 40:
            this.setState({ width: this.state.width - 1 })
            break
        }
    }

    position = (event) => {
        const handlePosition = event.touches ? event.touches[0].clientX : event.clientX
        const handleOffset = this.handleReference.getBoundingClientRect().width / 2
        const bodyOffset = this.bodyReference.getBoundingClientRect().width / 2

        return ((handlePosition - handleOffset - bodyOffset) * 2) || 375
    }

    render () {
        const sizes = {
            hand: {
                minimum: 360,
                maximum: 500,
            },
            lap: {
                minimum: 500,
                maximum: 800,
            },
            desk: {
                minimum: 800,
                maximum: 1280,
            },
        }

        return (<div className='c-pattern-library'>
            <div className='c-pattern-library__header'>
                <ul className='c-pl-menu'>
                    {_.toArray(_.map(patterns, (children, name) => {
                    return (<li key={name} className={classnames({
                        'c-pl-menu__item': true,
                        'c-pl-menu__item--selected': this.state.category === name,
                    })}>
                        <button onClick={() => { this.setState({ category: this.state.category === name ? null : name }) }}>
                            {displayName(name)}
                        </button>
                        <div className='c-pl-menu__content'>
                            <ul className='c-pl-content'>
                                <li className='c-pl-content__item'>
                                    <button onClick={() => { this.selectPattern(name) }}>
                                        <span role='img' aria-label='View All'>🔮</span> All
                                    </button>
                                </li>
                                {_.map(children, (child, childName) => {
                                return (<li key={name + '.' + childName} className='c-pl-content__item'>
                                    <button onClick={() => { this.selectPattern(name + '.' + childName) }}>
                                        {displayName(childName)}
                                    </button>
                                    {isReactComponent(child) ? null : <ul className='c-pl-sub-content'>
                                        {_.map(child, (grandChild, grandChildName) => {
                                            return (<li key={name + '.' + childName + '.' + grandChildName} className='c-pl-sub-content__item'>
                                                <button onClick={() => { this.selectPattern(name + '.' + childName + '.' + grandChildName) }}>
                                                    {displayName(grandChildName)}
                                                </button>
                                            </li>)
                                        })}
                                    </ul>}
                                </li>)
                            })}
                            </ul>
                        </div>
                    </li>)
                }))}
                </ul>
                <div className='c-pl-spacer' />
                <ul className='c-pl-menu'>
                    <li className='c-pl-menu__item'>
                        <input
                            type='text'
                            value={this.state.width}
                            onChange={(event) => {
                                this.setState({ width: +event.target.value })
                            }} />&thinsp;px&nbsp;
                    </li>
                    {_.toArray(_.map(sizes, (size, name) => {
                    return (<li key={name} className={classnames({
                        'c-pl-menu__item': true,
                        'c-pl-menu__item--selected': this.state.size === name,
                    })}>
                        <button onClick={() => {
                            this.setState({
                                size: name,
                                width: _.random(sizes[name].minimum, sizes[name].maximum, false),
                            })
                        }}>
                            {displayName(name)}
                        </button>
                    </li>)
                }))}
                </ul>
            </div>
            <div
                ref={handleReference => {
                    this.bodyReference = handleReference
                }}
                className='c-pattern-library__body'>
                <iframe
                    title='Patterns'
                    className='c-patterns'
                    style={{ width: (this.state.width + 2) + 'px' }}
                    src={app.getRouter().path('Frontastic.Frontend.PatternLibrary.view', { pattern: this.props.pattern })}
                />
                <div
                    ref={handleReference => {
                        this.handleReference = handleReference
                    }}
                    className='c-patterns__handle'
                    onMouseDown={this.handleStart}
                    onTouchMove={this.handleDrag}
                    onTouchEnd={this.handleEnd}
                    onKeyDown={this.handleKeyDown}
                    style={{
                        left: `calc(50% + (${this.state.width}px / 2))`,
                    }}
                    tabIndex={0}
                />
            </div>
        </div>)
    }
}

PatternLibrary.propTypes = {
    pattern: PropTypes.string.isRequired,
}

PatternLibrary.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            pattern: globalState.app.route.get('pattern', 'atoms'),
        }
    },
)(PatternLibrary)
