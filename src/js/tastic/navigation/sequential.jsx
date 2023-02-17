//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Link from '../../app/link'

import SvgIcon from '../../patterns/atoms/icons/icon'

class Sequential extends Component {
    constructor (props) {
        super(props)

        this.state = {
            showChildNavigation: null,
        }
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<Fragment>
            <div className={'c-sequential-nav__panel c-sequential-nav__panel--level-' + this.props.level + (this.props.pulledLeft ? ' is-pulled-left' : '')}>
                <div className='c-sequential-nav__header'>
                    {this.props.root.name ? <h3 className='c-sequential-nav__title'>{this.props.root.name}</h3> : null}
                    <button className='c-sequential-nav__primary-action c-button' onClick={this.props.onClose}>
                        <SvgIcon icon={this.props.level === 1 ? 'cross' : 'arrow-left'} />
                    </button>
                </div>
                <div className='c-sequential-nav__content'>
                    <ul className='c-tableview'>
                        {_.map(this.props.root.children, (node) => {
                            return (<li key={node.nodeId} className='c-tableview__cell'>
                                <Link
                                    className={
                                        'c-tableview__link' +
                                        (node.configuration.displayHighlightMenuItem ? ' c-tableview__link--primary' : '')
                                    }
                                    route={'node_' + node.nodeId}>
                                    {node.name}
                                </Link>
                                {node.children.length ?
                                    <button
                                        className='c-button c-tableview__button c-tableview__button--deep'
                                        onClick={() => {
                                            this.setState({ showChildNavigation: node.nodeId })
                                        }}>
                                        <SvgIcon icon='chevron-right' />
                                    </button> : null }
                            </li>)
                        })}
                    </ul>
                </div>
            </div>
            {_.map(this.props.root.children, (node) => {
                if (!node.children.length) {
                    return null
                }

                return <Sequential
                    key={'subtree_' + node.nodeId}
                    root={node}
                    level={this.props.level + 1}
                    pulledLeft={this.state.showChildNavigation === node.nodeId}
                    onClose={() => {
                        this.setState({ showChildNavigation: null })
                }} />
            })}
        </Fragment>)
    }
}

Sequential.propTypes = {
    root: PropTypes.object.isRequired,
    onClose: PropTypes.func.isRequired,
    pulledLeft: PropTypes.bool,
    level: PropTypes.number,
}

Sequential.defaultProps = {
    pulledLeft: false,
    level: 1,
}

export default Sequential
