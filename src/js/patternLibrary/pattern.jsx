import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import { displayName, isReactComponent } from './functions'

import Embed from './pattern/embed'
import Properties from './pattern/properties'

class Pattern extends Component {
    constructor (props) {
        super(props)

        this.state = {
            showProperties: false,
            showEmbed: false,
        }
    }

    render () {
        if (isReactComponent(this.props.pattern)) {
            let Component = this.props.pattern.component
            let testProps = Component.testProps || {}
            return (<Fragment>
                <h6 className='c-p-source'>Source: <strong>{this.props.pattern.source}</strong></h6>
                <div className='c-p-options'>
                    <button
                        className={'c-p-options__option' + (this.state.showProperties ? ' c-p-options__option--active' : '')}
                        onClick={() => { this.setState({ showProperties: !this.state.showProperties }) }}>
                        Props
                    </button>
                    <button
                        className={'c-p-options__option' + (this.state.showEmbed ? ' c-p-options__option--active' : '')}
                        onClick={() => { this.setState({ showEmbed: !this.state.showEmbed }) }}>
                        Embed
                    </button>
                </div>
                {this.state.showProperties ? <Properties component={Component} /> : null}
                {this.state.showEmbed ? <Embed component={this.props.pattern} /> : null}
                <Component {...testProps} />
            </Fragment>)
        }

        let Title = 'h' + this.props.level
        return _.toArray(_.map(this.props.pattern, (displayPatterns, name) => {
            return (<Fragment key={name}>
                <Title className={'c-patterns__title c-patterns__title--' + this.props.level}>
                    {displayName(name)}
                </Title>
                <div style={{ position: 'relative' }}>
                    <Pattern pattern={displayPatterns} level={this.props.level + 1} />
                </div>
            </Fragment>)
        }))
    }
}

Pattern.propTypes = {
    pattern: PropTypes.any.isRequired,
    level: PropTypes.number,
}

Pattern.defaultProps = {
    level: 2,
}

export default Pattern
