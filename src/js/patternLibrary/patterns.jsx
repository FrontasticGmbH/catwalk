import React, { Component } from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'
import _ from 'lodash'

import Pattern from './pattern'
import patterns from './patternList'

class PatternLibrary extends Component {
    render () {
        const displayPatterns = _.get(patterns, this.props.pattern)

        return <Pattern pattern={displayPatterns} />
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
