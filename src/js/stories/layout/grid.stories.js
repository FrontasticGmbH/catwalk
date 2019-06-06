import React from 'react'

import { storiesOf } from '@storybook/react'
import { action } from '@storybook/addon-actions'
import { withInfo } from '@storybook/addon-info'
import { withKnobs, boolean } from '@storybook/addon-knobs'

import Grid from 'js/patterns/atoms/grid/grid'
import Cell from 'js/patterns/atoms/grid/cell'
//import HeroSlider from 'js/patterns/organisms/slider/hero-slider

const debugDefault = true

const stories = storiesOf('Layout/Grid', module).addDecorator(withInfo)
stories.addDecorator(withKnobs)

stories.add('Basic Grid', () => {
    return (
        <Grid debug={boolean('Debug', debugDefault)}>
            <Cell size={`1/1`}>1/1</Cell>

            <Cell size={`1/2`}>1/2</Cell>
            <Cell size={`1/2`}>1/2</Cell>

            <Cell size={`1/3`}>1/3</Cell>
            <Cell size={`1/3`}>1/3</Cell>
            <Cell size={`1/3`}>1/3</Cell>

            <Cell size={`1/3`}>1/3</Cell>
            <Cell size={`1/3`}>1/3</Cell>
            <Cell size={`1/3`}>1/3</Cell>

            <Cell size={`1/3`}>1/3</Cell>
            <Cell size={`2/3`}>2/3</Cell>

            <Cell size={`1/4`}>1/4</Cell>
            <Cell size={`1/4`}>1/4</Cell>
            <Cell size={`1/4`}>1/4</Cell>
            <Cell size={`1/4`}>1/4</Cell>

            <Cell size={`1/4`}>1/4</Cell>
            <Cell size={`1/4`}>1/4</Cell>
            <Cell size={`1/2`}>1/2</Cell>

            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>

            <Cell size={`1/4`}>1/4</Cell>
            <Cell size={`1/4`}>1/4</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>

            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/6`}>1/6</Cell>
            <Cell size={`1/2`}>1/2</Cell>
        </Grid>
    )
})

stories.add('Full width Element', () => {
    return (
        <Grid debug={boolean('Debug', debugDefault)}>
            <Cell>12 (default)</Cell>
            <Cell fullWidth>fullwidth</Cell>
            <Cell size={6}>6</Cell>
            <Cell size={6}>6</Cell>
        </Grid>
    )
})

stories.add('Full width Element w/ slider', () => {
    return (
        <Grid debug={boolean('Debug', debugDefault)}>
            <Cell>12 (default)</Cell>
            <Cell fullWidth>{/*<HeroSlider />*/}</Cell>
            <Cell size={6}>6</Cell>
            <Cell size={6}>6</Cell>
        </Grid>
    )
})

stories.add('Hide Elements from devices', () => {
    return (
        <Grid debug={boolean('Debug', debugDefault)}>
            <Cell hideOn={['lap', 'desk']}>Hand only</Cell>

            <Cell hideOn={'lap'}>Hand & Desk</Cell>
            <Cell hideOn={['hand', 'desk']}>Lap only</Cell>
        </Grid>
    )
})
