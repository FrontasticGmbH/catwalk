import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'

class Card extends Component {
    tastics = window ? window.tastics : global.tastics

    renderTastic () {
        if (!this.props.data.cardTastic) { return null }

        const { tasticType, tasticData } = this.props.data.cardTastic
        const Tastic = this.tastics[tasticType]
        return (
            <Fragment>
                <p>Nested Tastic (Type: {tasticType})</p>
                <Tastic data={tasticData} />
            </Fragment>
        )
    }

    render () {
        return (
            <div className='card'>
                <div className='card-header'>
                    <div className='row align-items-center'>
                        <div className='col'>
                            <h4 className='card-header-title'>
                                {this.props.data.cardTitle}
                            </h4>
                        </div>
                    </div>
                </div>
                <div className='card-body'>
                    <span>Tastic comes here</span>
                    {/* {this.renderTastic()} */}
                </div>
            </div>
        )
    }
}

Card.propTypes = {
    data: PropTypes.object.isRequired,
}

Card.defaultProps = {}

export default Card
