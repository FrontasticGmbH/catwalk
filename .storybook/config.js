import React from 'react'
import { configure, addDecorator, addParameters } from '@storybook/react'
import StoryRouter from 'storybook-react-router'
import { create } from '@storybook/theming'
import { withInfo } from '@storybook/addon-info'
import { withFrontasticRedux } from '../src/js/helper/storybook/redux'

import Container from './Container'
import '../src/scss/app.scss'

addParameters({
    viewport: {
        viewports: {
            full: {
                name: 'Full',
                styles: {
                    width: '100%',
                    height: '100%',
                },
            },
            desk: {
                name: 'Desk',
                styles: {
                    width: '1280px',
                    height: '100%',
                },
            },
            LapSmall: {
                name: 'Lap Small',
                styles: {
                    width: '769px',
                    height: '100%',
                },
            },
            LapBig: {
                name: 'Lap Big',
                styles: {
                    width: '1280px',
                    height: '100%',
                },
            },
            handSmall: {
                name: 'Hand Small',
                styles: {
                    width: '360px',
                    height: '100%',
                },
            },
            handBig: {
                name: 'Hand Big',
                styles: {
                    width: '768px',
                    height: '100%',
                },
            },
        },
    },
})

addParameters({
    options: {
        theme: create({
            base: 'light',
            brandTitle: 'Frontastic',
            brandUrl: 'https://frontastic.cloud',
            brandImage: 'https://www.frontastic.cloud/wp-content/uploads/2017/08/frontastic-logo-space-2-300x66.png',
        }),
        isFullscreen: false,
        panelPosition: 'right',
    },
})

addDecorator(StoryRouter())
addDecorator(withFrontasticRedux)
addDecorator((story) => <Container story={story} />)

// automatically import all files ending in *.stories.js
configure(require.context('../src/js', true, /\.stories\.js$/), module)
