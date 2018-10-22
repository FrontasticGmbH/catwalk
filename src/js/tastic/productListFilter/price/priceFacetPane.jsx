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

    static getDerivedStateFromProps = (props, state) => {
        if (!state.range || !props.valueFromTastic) {
            return {
                range: [
                    _.toInteger((props.facetValue || {}).min || props.facet.min),
                    _.toInteger((props.facetValue || {}).max || props.facet.max),
                ],
            }
        }
        return null
    }

    render () {
        let min = this.props.facet.min
        let max = this.props.facet.max
        let step = this.props.facet.step || 1

        return (<div className='c-range-selector'>
            <form className='c-form' action=''>

                <div className='c-range-selector__text-fields'>

                    <div className='c-form__item-inline'>
                        <input
                            name='price-range-min'
                            type='number'
                            className='c-form__input-text'
                            value={(this.state.range[0] / 100).toFixed(2)}
                            min={min / 100}
                            max={this.state.range[1] / 100}
                            step={step / 100}
                            onChange={this.updateLower}
                        />
                    </div>

                    <div className='c-form__item-inline'>
                        <input
                            name='price-range-max'
                            type='number'
                            className='c-form__input-text'
                            value={(this.state.range[1] / 100).toFixed(2)}
                            min={(this.state.range[0] / 100)}
                            max={max / 100}
                            step={step / 100}
                            onChange={this.updateUpper}
                        />
                    </div>

                </div>

                <div className='c-form__item'>
                    <Range
                        min={min}
                        max={max}
                        step={step}
                        value={this.state.range}
                        allowCross={false}
                        onChange={(range) => {
                            this.updateRange(range)
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
        }, this.updateFacet)
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

    updateFacet = _.debounce(() => {
        if (_.isEqual(this.state.range, [this.props.facet.min, this.props.facet.max])) {
            this.props.removeFacetValue(this.props.facet)
        } else {
            this.props.selectFacetValue(
                this.props.facet,
                {
                    min: this.state.range[0],
                    max: this.state.range[1],
                }
            )
        }
    }, 100)
}

PriceFacetPane.propTypes = {
    facet: PropTypes.object.isRequired,
    // eslint-disable-next-line react/no-unused-prop-types
    facetValue: PropTypes.object, // used in getDerivedStateFromProps() as props.
    // eslint-disable-next-line react/no-unused-prop-types
    valueFromTastic: PropTypes.bool, // used in getDerivedStateFromProps() as props.
    selectFacetValue: PropTypes.func.isRequired,
    removeFacetValue: PropTypes.func.isRequired,
}

PriceFacetPane.defaultProps = {
    valueFromTastic: false,
}

export default PriceFacetPane
