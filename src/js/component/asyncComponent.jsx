import React from 'react'

const asyncComponent = (component) => {
    return class extends React.Component {
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
                // @TODO: Is there a sensible way to pre-render height device
                // specific?
                return (
                    <div
                        className='c-asyc-component'
                        style={{ height: component.height.desktop + 'px' }}
                    />
                )
            }

            return <InnerComponent {...this.props} />
        }
    }
}

export default asyncComponent
