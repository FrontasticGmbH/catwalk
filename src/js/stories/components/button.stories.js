import React from 'react'

import { action } from '@storybook/addon-actions'

import Button from '../../patterns/atoms/buttons/button'

export default {
    title: 'Button',
}

export const defaultButton = () => {
    return (
        <div>
            <p>
                <Button onClick={action('clicked')}>Default Button</Button>
            </p>
        </div>
    )
}
export const primary = () => {
    return (
        <div>
            <p>
                <Button type='primary' onClick={action('clicked')}>
                    Primary Button
                </Button>
            </p>
            <p>
                <Button type='primary' size='small' onClick={action('clicked')}>
                    Primary Small Button
                </Button>
            </p>
            <p>
                <Button type='primary' outline onClick={action('clicked')}>
                    Primary Outline Button
                </Button>
            </p>
            <p>
                <Button type='primary' rounded onClick={action('clicked')}>
                    Primary Rounded Button
                </Button>
            </p>
            <p>
                <Button type='primary' outline ghost onClick={action('clicked')}>
                    Primary Ghost Button
                </Button>
            </p>
            <p>
                <Button type='primary' full onClick={action('clicked')}>
                    Primary Full Button
                </Button>
            </p>
        </div>
    )
}
export const secondary = () => {
    return (
        <div>
            <p>
                <Button type='secondary' onClick={action('clicked')}>
                    Secondary Button
                </Button>
            </p>
            <p>
                <Button type='secondary' size='small' onClick={action('clicked')}>
                    Secondary Button
                </Button>
            </p>
        </div>
    )
}
