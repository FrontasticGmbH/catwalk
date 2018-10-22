import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsButton from '../10-buttons/10-button'
import AtomsIcon from '../40-icons/10-icon'

class AtomsButtons extends Component {
    render () {
        return (<Fragment>
            <p><AtomsButton>Button</AtomsButton></p>
            <p><AtomsButton component='a' href='#'>Link Button</AtomsButton></p>

            <p><AtomsButton type='primary'>Button Primary</AtomsButton></p>
            <p><AtomsButton type='secondary'>Button Secondary</AtomsButton></p>
            <p><AtomsButton type='primary' outline>Primary Outline</AtomsButton></p>

            <p><AtomsButton type='primary' ghost outline>Primary Ghost Outline</AtomsButton></p>
            <p><AtomsButton ghost>Ghost Button</AtomsButton></p>

            <p>
                <AtomsButton type='secondary'>
                    <span>Button Secondary with Icon after</span>
                    <AtomsIcon icon='bag' className='c-button__icon' />
                </AtomsButton>
            </p>

            <p>
                <AtomsButton type='secondary' rounded>
                    <AtomsIcon icon='bag' className='c-button__icon' />
                    <span>Button Secondary Rounded with Icon</span>
                </AtomsButton>
            </p>

            <p><AtomsButton type='primary' full>Button Full</AtomsButton></p>

            <p>
                <AtomsButton type='primary' full>
                    <span>Button Full with Icon</span>
                    <AtomsIcon icon='bag' className='c-button__icon' />
                </AtomsButton>
            </p>

            <p><AtomsButton type='primary' size='small'>Button Primary Small</AtomsButton></p>
            <p><AtomsButton type='primary' size='large'>Button Primary Large</AtomsButton></p>

            <p>
                <span className='c-button-row'>
                    <AtomsButton type='primary' outline>Button Outline</AtomsButton>
                    <AtomsButton type='primary'>Button Primary</AtomsButton>
                    <AtomsButton type='secondary'>Button Secondary</AtomsButton>
                </span>
            </p>
        </Fragment>)
    }
}

AtomsButtons.propTypes = {
}

AtomsButtons.defaultProps = {
}

export default ComponentInjector.return('AtomsButtons', AtomsButtons)
