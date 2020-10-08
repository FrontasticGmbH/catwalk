import React, { Component, createElement } from 'react' // eslint-disable-line no-unused-vars
import { findDOMNode } from 'react-dom'
import hoistStatics from 'hoist-non-react-statics'
import raf from 'raf'

const DEFAULT_GET_SIZE = (domElement) => {
    return { width: domElement.clientWidth, height: domElement.clientHeight }
}
const DEFAULT_PLACEHOLDER_STYLE = {
    overflow: 'visible',
    height: 0,
    width: 0,
}
const DEFAULT_WIDTH_PROP = 'width'
const DEFAULT_HEIGHT_PROP = 'height'
const DEFAULT_RESIZE_PROPS = []
const DEFAULT_UPDATE_SIZE_CALLBACK = () => {}
const DEFAULT_UPDATE_SIZE_CALLBACK_PROP = 'updateSizeCallback'
const DEFAULT_GET_DISPLAY_NAME = (name) => {
    return `Sizer(${name})`
}

export default function sizer ({
    getSize = DEFAULT_GET_SIZE,
    placeholderStyle = DEFAULT_PLACEHOLDER_STYLE,
    widthProp = DEFAULT_WIDTH_PROP,
    heightProp = DEFAULT_HEIGHT_PROP,
    getSizeProps = null,
    resizeProps = DEFAULT_RESIZE_PROPS,
    updateSizeCallback = DEFAULT_UPDATE_SIZE_CALLBACK,
    updateSizeCallbackProp = DEFAULT_UPDATE_SIZE_CALLBACK_PROP,
    getDisplayName = DEFAULT_GET_DISPLAY_NAME,
} = {}) {
    return function _sizer (WrappedComponent) {
        const wrappedComponentName =
            (WrappedComponent && (WrappedComponent.displayName || WrappedComponent.name)) || 'Component'

        const displayName = wrappedComponentName ? getDisplayName(wrappedComponentName) : 'sizer-none'

        class Placeholder extends Component {
            render () {
                return createElement('span', { style: placeholderStyle, ref: this.props.placeholderRef }) // eslint-disable-line react/prop-types
            }
        }

        class Sizer extends Component {
            constructor (props) {
                super(props)
                this.state = { width: null, height: null }
                this.DOMNode = null
                this.parentDOMNode = null
                this.setWrappedInstance = this.setWrappedInstance.bind(this)
                this.onResize = this.onResize.bind(this)
            }

            setWrappedInstance (ref) {
                this.wrappedInstance = ref
                if (ref) {
                    this.DOMNode = findDOMNode(ref)
                    this.parentDOMNode = this.DOMNode ? this.DOMNode.parentNode : null
                } else {
                    this.DOMNode = null
                    this.parentDOMNode = null
                }
            }

            getWindow () {
                return this.DOMNode?.ownerDocument?.defaultView || window // eslint-disable-line no-undef
            }

            onResize () {
                if (!this.rafId) {
                    this.rafId = raf(() => {
                        this.rafId = null
                        this.updateSize()
                    })
                }
            }

            updateSize () {
                if (this.parentDOMNode) {
                    const { width: oldWidth, height: oldHeight } = this.state
                    const { width, height } = getSize(this.parentDOMNode)
                    if (width !== oldWidth || height !== oldHeight) {
                        this.setState({ width, height }, () => {
                            updateSizeCallback()
                            if (updateSizeCallbackProp && this.props[updateSizeCallbackProp]) {
                                this.props[updateSizeCallbackProp](width, height)
                            }
                        })
                    }
                }
            }

            componentDidMount () {
                this.updateSize()
                this.getWindow().addEventListener('resize', this.onResize, false)
            }

            componentWillUnmount () {
                this.getWindow().removeEventListener('resize', this.onResize)
            }

            componentDidUpdate (prevProps) {
                const nextProps = this.props
                if (
                    resizeProps.some((resizeProp) => {
                        return nextProps[resizeProp] !== prevProps[resizeProp]
                    })
                ) {
                    this.onResize()
                }
            }

            render () {
                const props = this.props
                const { width, height } = this.state

                if (WrappedComponent) {
                    if (getSizeProps) {
                        const sizeProps = getSizeProps({ width, height })
                        return createElement(WrappedComponent, { ...props, ...sizeProps, ref: this.setWrappedInstance })
                    } else {
                        return createElement(WrappedComponent, {
                            ...props,
                            [widthProp]: width,
                            [heightProp]: height,
                            ref: this.setWrappedInstance,
                        })
                    }
                } else {
                    return createElement(Placeholder, { placeholderRef: this.setWrappedInstance })
                }
            }
        }

        Sizer.displayName = displayName
        Sizer.WrappedComponent = WrappedComponent

        return WrappedComponent ? hoistStatics(Sizer, WrappedComponent) : Sizer
    }
}
