import { useSelector } from 'react-redux'

/**
 * A hook that returns the current device type based on screen width.
 *
 * The value is just a string in the redux store (mobile, table or desktop),
 * but recalculated on every screen resize (see renderContext.js).
 * The breakpoint settings are set in the project's project.yml
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
