import React from 'react'
import { connect } from 'react-redux'
import PropTypes from 'prop-types'

const mapStateToProps = (globalState) => {
    return {
        deviceType: globalState.renderContext.deviceType,
    }
}

const asyncComponent = (component) =>
    connect(mapStateToProps)(class extends React.Component {
        static propTypes = {
            deviceType: PropTypes.string,
        }

        state = {
            component: null,
        }

        componentDidMount () {
            component.import()
                .then(component => {
                    this.setState({ component: component.default })
                })
        }

        render () {
            const InnerComponent = this.state.component

            if (!InnerComponent) {
                return (
                    <div
                        className='c-asyc-component'
                        style={{ height: component.height[this.props.deviceType] || component.height.mobile + 'px' }}
                    />
                )
            }

            return <InnerComponent {...this.props} />
        }
    })

export default asyncComponent
