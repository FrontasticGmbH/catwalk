import React from 'react' // eslint-disable-line no-unused-vars
import { useSelector } from 'react-redux'
import useComponentSize from '@rehooks/component-size'
import MediaApi from 'frontastic-common/src/js/mediaApi'

function useBackgroundImageUrl (ref, mediaObject) {
    const projectConf = useSelector((state) => {
        return state.app.context.project.configuration
    })
    const { width, height } = useComponentSize(ref)

    if (!mediaObject || !mediaObject.media) {
        return null
    }

    const mediaApi = new MediaApi()

    return mediaApi.getImageLink(mediaObject.media, projectConf, width, height)
}

export default useBackgroundImageUrl
