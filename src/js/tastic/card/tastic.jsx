//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Tastic } from 'frontastic-common'

class Card extends Component {
    tastics = window ? window.tastics : global.tastics

    renderEmptyTastic () {
        return (
            <span>Warning: Add a tastic in the configuration</span>
        )
    }

    renderTastic () {
        if (!this.props.data.cardTastic) { return this.renderEmptyTastic() }

        const { tasticType, tasticData, tasticObject } = this.props.data.cardTastic
        if (!tasticType || tasticType === '') { return this.renderEmptyTastic() }

        const TasticComponent = this.tastics[tasticType]
        const nestedTastic = new Tastic({
            tasticType,
            ...tasticObject.schema,
        })

        return <TasticComponent data={tasticData} tastic={nestedTastic} />
    }

    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

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
                    {this.renderTastic()}
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
