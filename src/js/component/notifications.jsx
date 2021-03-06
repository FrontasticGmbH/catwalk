import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'

import app from '../app/app'

import AtomsNotification from '../patterns/atoms/notifications/notification'

class Notifications extends Component {
    render () {
        return Object.values(this.props.notifications).map((notification) => {
            return (<AtomsNotification open
                key={notification.notificationId}
                onClose={() => {
                    app.getStore().dispatch({
                        type: 'Frontastic.Notification.remove',
                        id: notification.notificationId,
                    })
                }}
                autoHideDuration={notification.timeout || null}
                type={notification.type}
                message={notification.message}
            />)
        })
    }
}

Notifications.propTypes = {
    notifications: PropTypes.object.isRequired,
}

Notifications.defaultProps = {
}

export default connect(
    (globalState, props) => {
        return {
            notifications: globalState.user.notifications,
        }
    }
)(Notifications)
