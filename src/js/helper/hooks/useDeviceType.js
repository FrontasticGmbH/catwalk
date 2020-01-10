import { useSelector } from 'react-redux'

export function useDeviceType () {
    const deviceType = useSelector((state) => {
        return state.renderContext.deviceType
    })

    return deviceType
}
