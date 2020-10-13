//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsNotification from './notification'

class AtomsNotifications extends Component {
    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (<Fragment>
            <AtomsNotification message='Default Info Notification' />

            <AtomsNotification type='success' message='Success Notification' />
            <AtomsNotification type='info' message='Info Notification' />
            <AtomsNotification type='warning' message='Warning Notification' />
            <AtomsNotification type='error' message='Error Notification' />

            <AtomsNotification type='error' onClose={() => {}} message='Error Notification with close button' />
        </Fragment>)
    }
}

AtomsNotifications.propTypes = {
}

AtomsNotifications.defaultProps = {
}

export default ComponentInjector.return('AtomsNotifications', AtomsNotifications)
