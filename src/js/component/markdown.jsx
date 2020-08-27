import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { markdown } from 'markdown'

import getTranslation from '../getTranslation'
import ComponentInjector from '../app/injector'

import app from '../app/app'

class Markdown extends Component {
    componentDidMount = () => {
        let links = this.refs.markdown.querySelectorAll('a')

        (links || []).map((link) => {
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

        return (<div className={this.props.className} ref='markdown' dangerouslySetInnerHTML={{
            __html: markdown.toHTML(text.text || ''),
        }} />)
    }
}

Markdown.propTypes = {
    context: PropTypes.object.isRequired,
    className: PropTypes.string,
    text: PropTypes.oneOfType([
        PropTypes.object,
        PropTypes.string,
    ]).isRequired,
}

Markdown.defaultProps = {
    className: 'c-markdown s-text',
}

export default connect(
    (globalState, props) => {
        return {
            context: globalState.app.context,
            ...props,
        }
    }
)(ComponentInjector.return('Markdown', Markdown))
