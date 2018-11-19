import React, { Component, Fragment } from 'react'

import ComponentInjector from '../../../app/injector'

import AtomsNotification from './10-notification'

class AtomsNotifications extends Component {
    render () {
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
