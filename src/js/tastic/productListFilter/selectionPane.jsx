import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import _ from 'lodash'

import iconCross from '../../../icons/icomoon_icons/SVG/cross.svg'
import iconArrowLeft from '../../../icons/icomoon_icons/SVG/arrow-left.svg'
import Translatable from '../../component/translatable'

import PriceFacetPane from './price/priceFacetPane'
import PriceFacetTitle from './price/priceFacetTitle'
import TermFacetPane from './term/termFacetPane'
import TermFacetTitle from './term/termFacetTitle'

class SelectionPane extends Component {

    attributeTypeMap = {
        enum: {
            selector: TermFacetPane,
            title: TermFacetTitle,
        },
        localizedEnum: {
            selector: TermFacetPane,
            title: TermFacetTitle,
        },
        money: {
            selector: PriceFacetPane,
            title: PriceFacetTitle,
        }
    }

    constructor (props) {
        super(props)

        this.state = {
            displayFacetDetails: null,
        }
    }

    viewModel = () => {
        return _.map(this.props.facetConfiguration, (facetConfig) => {
            return {
                config: facetConfig,
                facet: _.find(this.props.facets, { handle: facetConfig.attributeId }),
            }
        })
    }

    render () {
        const viewModel = this.viewModel()

        return (<Fragment>
            <div className={'c-overlay' + (this.props.show ? ' is-visible c-overlay-outsidevabar-isvisible' : '')}>
                <nav className='c-sequential-nav' data-ft-sequential-nav=''>
                    {/* 1 = level, should be higher on selected filter */}
                    <div className={'c-sequential-nav__panel c-sequential-nav__panel--level-1'}>
                        <div className='c-sequential-nav__header'>
                            <h3 className='c-sequential-nav__title'>Filter</h3>
                            <button className='c-sequential-nav__primary-action c-button' onClick={this.props.onClose}>
                                <img className='o-icon' data-icon='cross' data-icon-size='base' src={iconCross} alt='Close' />
                            </button>
                        </div>
                        <div className='c-sequential-nav__content'>
                            <ul className='c-tableview'>
                                {_.map(
                                    viewModel,
                                    (facetData) => {
                                        const TitleComponent = this.attributeTypeMap[facetData.config.attributeType].title
                                        const facet = facetData.facet

                                        return (<li key={facet.handle} className='c-tableview__cell'>
                                            <button
                                                className={'c-button c-tableview__button' + (facet.selected ? ' is-active' : '')}
                                                onClick={() => {
                                                    this.showFacet(facet)
                                                }}>
                                                {<TitleComponent
                                                    facet={facet}
                                                    facetValue={this.getFacetValue(facet)}
                                                    name={<Translatable value={facetData.config.label} />}
                                                />}
                                            </button>
                                        </li>)
                                    }
                                )}
                            </ul>
                        </div>
                    </div>
                    {_.map(viewModel, this.renderFacetPane)}
                </nav>
            </div>

        </Fragment>)
    }

    getFacetValue = (facet) => {
        return this.props.facetValues[facet.handle] || null
    }

    renderFacetPane = (facetData) => {
        const facet = facetData.facet

        const ComponentClass = this.attributeTypeMap[facetData.config.attributeType].selector

        return (<div
            key={'details-' + facetData.config.attributeId}
            className={'c-sequential-nav__panel' +
                ' c-sequential-nav__panel--level-2' +
                (this.state.displayFacetDetails === facet.key ? ' is-pulled-left' : '')}
            >
            <div className='c-sequential-nav__header'>
                <h3 className='c-sequential-nav__title'><Translatable value={facetData.config.label} /></h3>
                <button className='c-sequential-nav__primary-action c-button' onClick={this.closeFacet}>
                    <img className='o-icon' data-icon='cross' data-icon-size='base' src={iconArrowLeft} alt='Return' />
                </button>
            </div>
            <div className='c-sequential-nav__content'>
                <ComponentClass
                    facet={facet}
                    facetValue={this.getFacetValue(facet)}
                    valueFromTastic={this.props.valuesFromTastic}
                    selectFacetValue={this.props.selectFacetValue}
                    removeFacetValue={this.props.removeFacetValue}
                />
            </div>
        </div>)
    }

    showFacet = (facet) => {
        this.setState({
            displayFacetDetails: facet.key,
        })
    }

    closeFacet = () => {
        this.setState({
            displayFacetDetails: null,
        })
    }
}

SelectionPane.propTypes = {
    facets: PropTypes.array.isRequired,
    facetValues: PropTypes.object.isRequired,
    facetConfiguration: PropTypes.array.isRequired,

    valuesFromTastic: PropTypes.bool.isRequired,

    show: PropTypes.bool.isRequired,
    onClose: PropTypes.func.isRequired,

    selectFacetValue: PropTypes.func.isRequired,
    removeFacetValue: PropTypes.func.isRequired,
}

SelectionPane.defaultProps = {
    show: false,
}

export default SelectionPane
