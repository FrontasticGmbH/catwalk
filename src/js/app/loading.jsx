import React, { Component, Fragment } from 'react'
import PropTypes from 'prop-types'
import { Transition } from 'react-transition-group'

import ComponentInjector from '../app/injector'

import Spinner from '../../layout/loading.svg'

class LoadingState extends Component {
    render () {
        let isLoading = !this.props.entity || this.props.entity.loading
        let isErrored = !!(this.props.entity && this.props.entity.error)
        let size = this.props.large ? 128 : 32

        return (<Fragment>
            <Transition appear in={isLoading} timeout={115}>
                {(state) => {
                    return (<div className={'u-fade u-fade--' + state}>
                        <div className='c-overlay is-visible'>
                            <div className='c-loading'>
                                <img src={Spinner} width={size} height={size} alt='Loading' />
                            </div>
                        </div>
                    </div>)
                }}
            </Transition>
            {!isErrored ? null : <div className='o-layout__item u-1/1'>
                <div className='c-alert c-alert--error'>
                    <h2 className='c-alert__caption'>Error</h2>
                    <p>{this.props.entity.error.message || 'Internal Server Error'}</p>
                </div>
            </div>}
        </Fragment>)
    }
}

LoadingState.propTypes = {
    large: PropTypes.bool,
    entity: PropTypes.object,
}

LoadingState.defaultProps = {
    large: false,
    entity: null,
}

export default ComponentInjector.return('LoadingState', LoadingState)
