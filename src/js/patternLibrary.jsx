import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import classnames from 'classnames'
import _ from 'lodash'
import QRCode from 'qrcode.react'

import app from './app/app'

import patterns from './patternLibrary/patternList'
import { displayName, isReactComponent } from './patternLibrary/functions'

/* eslint-disable react/jsx-no-target-blank */
class PatternLibrary extends Component {
    constructor (props) {
        super(props)

        this.state = {
            category: null,
            size: 'hand',
            highlight: null,
            showLinks: false,
            search: '',
        }
    }

    index = this.compilePatternIndex(patterns, [])
    fuse

    componentDidMount = () => {
        // eslint-disable-next-line global-require
        const scriptjs = require(`scriptjs`)
        scriptjs(
            '//cdnjs.cloudflare.com/ajax/libs/fuse.js/3.4.0/fuse.min.js',
            () => {
                this.fuse = new Fuse(this.index, { keys: ['id', 'keys', 'name'] }) // eslint-disable-line
            }
        )
    }

    compilePatternIndex (patterns, index, prefix = '') {
        for (let key in patterns) {
            let id = (prefix ? prefix + '.' : '') + key

            if (patterns[key].component && patterns[key].path) {
                index.push(_.extend({ id, keys: id.split('.').join(' ') }, patterns[key]))
            } else {
                index = this.compilePatternIndex(patterns[key], index, id)
            }
        }

        return index
    }

    selectPattern (pattern) {
        app.getRouter().replace('Frontastic.Frontend.PatternLibrary.overview', { pattern: pattern, w: this.props.width })
        this.setState({ category: null })
    }

    render () {
        const sizes = {
            hand: {
                minimum: 360,
                maximum: 768,
            },
            lap: {
                minimum: 769,
                maximum: 1280,
            },
            desk: {
                minimum: 1280,
                maximum: 1920,
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
                    <li key='__search' className={classnames({
                        'c-pl-menu__item': true,
                        'c-pl-menu__item--selected': !!this.state.search,
                    })}>
                        <input
                            className='c-pl-menu__input--search'
                            type='text'
                            placeholder='Search Patterns…'
                            value={this.props.search}
                            onChange={(event) => {
                                this.setState({ search: event.target.value })
                            }}
                        />
                        <div className='c-pl-menu__content'>
                            <ul className='c-pl-content'>
                                {this.state.search && this.fuse.search(this.state.search).slice(0, 10).map((result) => {
                                    return (<li className='c-pl-content__item' key={result.id}>
                                        <button onClick={() => {
                                            this.setState({ search: '' })
                                            this.selectPattern(result.id)
                                        }}>
                                            {result.name} <small>({result.id})</small>
                                        </button>
                                    </li>)
                                })}
                            </ul>
                        </div>
                    </li>
                </ul>
                <div className='c-pl-spacer' />
                <ul className='c-pl-menu'>
                    <li className='c-pl-menu__item'>
                        <input
                            className='c-pl-menu__input--width'
                            type='text'
                            value={this.props.width}
                            onChange={(event) => {
                                app.getRouter().replace(
                                    'Frontastic.Frontend.PatternLibrary.overview',
                                    {
                                        pattern: this.props.pattern,
                                        w: event.target.value,
                                    }
                                )
                            }} />&thinsp;px&nbsp;
                    </li>
                    {_.toArray(_.map(sizes, (size, name) => {
                        return (<li key={name} className={classnames({
                            'c-pl-menu__item': true,
                            'c-pl-menu__item--selected': this.state.size === name,
                        })}>
                            <button onClick={() => {
                                this.setState({ size: name })
                                app.getRouter().replace(
                                    'Frontastic.Frontend.PatternLibrary.overview',
                                    {
                                        pattern: this.props.pattern,
                                        w: _.random(sizes[name].minimum, sizes[name].maximum, false),
                                    }
                                )
                            }}>
                                {displayName(name)}
                            </button>
                        </li>)
                    }))}
                    <li className={'c-pl-menu__item' + (this.state.showLinks ? ' c-pl-menu__item--selected' : '')}>
                        <button onClick={() => { this.setState({ showLinks: !this.state.showLinks }) }}>
                            <span role='img' aria-label='Anchor Symbol'>🔗</span>
                        </button>
                        <div className='c-pl-menu__content c-pl-menu__content--right'>
                            <ul className='c-pl-content'>
                                <li className='c-pl-content__item'>
                                    <a
                                        href={app.getRouter().path('Frontastic.Frontend.PatternLibrary.view', { pattern: this.props.pattern })}
                                        target='_blank'>
                                        Open in New Tab
                                    </a>
                                </li>
                                {this.props.tunnels.length ? <li className='c-pl-content__item'>
                                    <a
                                        href={this.props.tunnels[0] + app.getRouter().path('Frontastic.Frontend.PatternLibrary.view', { pattern: this.props.pattern })}
                                        target='_blank'>
                                        Open through ngrok:
                                    </a>
                                    <div className='qrcode'>
                                        <QRCode value={this.props.tunnels[0] + app.getRouter().path('Frontastic.Frontend.PatternLibrary.view', { pattern: this.props.pattern })} size={200} />
                                    </div>
                                </li> : null}
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div className='c-pattern-library__body'>
                <iframe
                    title='Patterns'
                    className='c-patterns'
                    style={{ width: (+this.props.width + 2) + 'px' }}
                    src={app.getRouter().path('Frontastic.Frontend.PatternLibrary.view', { pattern: this.props.pattern })}
                />
            </div>
        </div>)
    }
}

PatternLibrary.propTypes = {
    width: PropTypes.string.isRequired,
    pattern: PropTypes.string.isRequired,
    tunnels: PropTypes.array.isRequired,
    search: PropTypes.string,
}

PatternLibrary.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            ...props,
            width: globalState.app.route.get('w', '375'),
            pattern: globalState.app.route.get('pattern', 'atoms'),
            tunnels: globalState.dev.tunnels,
        }
    },
)(PatternLibrary)
