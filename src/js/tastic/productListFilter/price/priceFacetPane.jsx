//
// Deprecated: This component is deprecated and should not be used any more
//
import 'rc-slider/assets/index.css'

import React, { PureComponent } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import { Range } from 'rc-slider'

class PriceFacetPane extends PureComponent {
    constructor (props) {
        super(props)

        this.state = {
            range: null,
        }
    }

    getCurrentRange = () => {
        if (this.state.range) {
            return this.state.range
        }

        return [
            _.toInteger((this.props.facetValue || {}).min || this.props.facet.min),
            _.toInteger((this.props.facetValue || {}).max || this.props.facet.max),
        ]
    }

    startEditing = () => {
        this.setState({
            range: [
                _.toInteger((this.props.facetValue || {}).min || this.props.facet.min),
                _.toInteger((this.props.facetValue || {}).max || this.props.facet.max),
            ],
        })
    }

    finishEditing = () => {
        this.updateFacet(this.state.range)
        this.setState({
            range: null,
        })
    }

    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        const min = this.props.facet.min
        const max = this.props.facet.max
        const step = this.props.facet.step || 1

        const range = this.getCurrentRange()

        return (<div className='c-range-selector'>
            <form className='c-form' action=''>

                <div className='c-range-selector__text-fields'>

                    <div className='c-form__item-inline'>
                        <input
                            name='price-range-min'
                            type='number'
                            className='c-form__input-text'
                            value={(range[0] / 100).toFixed(2)}
                            min={min / 100}
                            max={range[1] / 100}
                            step={step / 100}
                            onChange={this.updateLower}
                            onFocus={this.startEditing}
                            onBlur={this.finishEditing}
                        />
                    </div>

                    <div className='c-form__item-inline'>
                        <input
                            name='price-range-max'
                            type='number'
                            className='c-form__input-text'
                            value={(range[1] / 100).toFixed(2)}
                            min={(range[0] / 100)}
                            max={max / 100}
                            step={step / 100}
                            onChange={this.updateUpper}
                            onFocus={this.startEditing}
                            onBlur={this.finishEditing}
                        />
                    </div>

                </div>

                <div className='c-form__item'>
                    <Range
                        min={min}
                        max={max}
                        step={step}
                        value={range}
                        allowCross={false}
                        onChange={(range) => {
                            this.updateRange(range)
                        }}
                        onBeforeChange={() => {
                            this.startEditing()
                        }}
                        onAfterChange={() => {
                            this.finishEditing()
                        }}
                        trackStyle={[{ backgroundColor: 'black' }, { backgroundColor: 'black' }]}
                        handleStyle={[{ borderColor: 'black' }, { borderColor: 'black' }]}
                        railStyle={{ backgroundColor: 'grey' }}
                    />
                </div>

            </form>
        </div>)
    }

    updateRange = (range) => {
        this.setState({
            range: _.cloneDeep(range),
        })
    }

    updateLower = (e) => {
        let range = _.cloneDeep(this.state.range)
        range[0] = Math.floor(e.currentTarget.value * 100)
        this.updateRange(range)
    }

    updateUpper = (e) => {
        let range = _.cloneDeep(this.state.range)
        range[1] = Math.ceil(e.currentTarget.value * 100)
        this.updateRange(range)
    }

    updateFacet = _.throttle((range) => {
        if (_.isEqual(range, [this.props.facet.min, this.props.facet.max])) {
            this.props.removeFacetValue(this.props.facet)
        } else {
            this.props.selectFacetValue(
                this.props.facet,
                {
                    min: range[0],
                    max: range[1],
                }
            )
        }
    }, 100)
}

PriceFacetPane.propTypes = {
    facet: PropTypes.object.isRequired,
    facetValue: PropTypes.object, // used in getDerivedStateFromProps() as props.
    selectFacetValue: PropTypes.func.isRequired,
    removeFacetValue: PropTypes.func.isRequired,
}

PriceFacetPane.defaultProps = {
    valueFromTastic: false,
}

export default PriceFacetPane
