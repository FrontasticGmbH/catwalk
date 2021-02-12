import React, { useState } from 'react'
import { connect } from 'react-redux'

const asyncComponent = (component) => {
    return connect((globalState, props) => {
        console.log(arguments)
        return {
            deviceType: globalState?.renderContext?.deviceType || 'mobile',
            ...props,
        }
    })((props) => {
        /*
        const [component, setComponent] = useState
        state = {
            component: null,
        }

        componentDidMount () {
            component.import()
                .then(component => {
                    this.setState({ component: component.default })
                })
        }

        render ({ deviceType = 'mobile' }) {
            const InnerComponent = this.state.component

            let height = component?.height?.desktop || 12
            if (deviceType && component.height[deviceType]) {
                height = component.height[deviceType]
            }

            console.log(deviceType, height)

            if (!InnerComponent) {
                return <div className='c-asyc-component' style={{ height: height + 'px' }} />
            }

            return <InnerComponent {...this.props} />
        }
        */
    })
}

export default asyncComponent
