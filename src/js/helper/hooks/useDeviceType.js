import { useSelector } from 'react-redux'

/**
 * A hook that returns the current device type based on screen width.
 *
 * The value is just a string in the redux store (mobile, table or desktop),
 * but recalculated via the renderContext's viewPortDimension
 * on every screen resize:
 * https://github.com/FrontasticGmbH/frontastic/blob/8552d74d1d6d15c512462947dea3296b884a77e6/paas/catwalk/src/js/app/reducer/renderContext.js#L41
 *
 * The breakpoint settings are set in the project's project.yml, e.g.:
 * https://github.com/FrontasticGmbH/frontastic/blob/04d8561c8b6b7fe5eca01bbd745a31c77bcd2739/demo_english/config/project.yml#L57
 *
 * Usage:
 * `const deviceType = useDeviceType() // 'mobile' | 'tablet' | 'desktop'`
 **/
export function useDeviceType () {
    const deviceType = useSelector((state) => {
        return state.renderContext.deviceType
    })

    return deviceType
}
