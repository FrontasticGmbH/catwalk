import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { getTranslation } from 'frontastic-common'
import { markdown } from 'markdown'
import _ from 'lodash'

import ComponentInjector from '../app/injector'

import app from '../app/app'

class Markdown extends Component {
    componentDidMount = () => {
        let links = this.refs.markdown.querySelectorAll('a')

        _.map(links, (link) => {
            link.onclick = (event) => {
                event.preventDefault()

                // @TODO: We might need handling for fragment links, too
                app.getRouter().history.push(link.getAttribute('href'))
            }
        })
    }

    render () {
        let text = getTranslation(
            this.props.text,
            this.props.context.locale,
            this.props.context.project.defaultLanguage
        )

        return (<div className='c-markdown s-text' ref='markdown' dangerouslySetInnerHTML={{
            __html: markdown.toHTML(text.text || ''),
        }} />)
    }
}

Markdown.propTypes = {
    context: PropTypes.object.isRequired,
    text: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.string,
    ]).isRequired,
}

Markdown.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            context: globalState.app.context,
            ...props,
        }
    }
)(ComponentInjector.return('Markdown', Markdown))
