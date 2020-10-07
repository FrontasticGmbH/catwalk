//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import * as d3 from 'd3'

const width = 650
const height = 400
const margin = { top: 20, right: 5, bottom: 20, left: 35 }
const red = '#eb6a5b'
const blue = '#52b6ca'

class LineChart extends Component {
    state = {
        highs: null, // svg path command for all the high temps
        lows: null, // svg path command for low temps,
        // d3 helpers
        xScale: d3.scaleTime().range([margin.left, width - margin.right]),
        yScale: d3.scaleLinear().range([height - margin.bottom, margin.top]),
        lineGenerator: d3.line(),
    };

    xAxis = d3.axisBottom().scale(this.state.xScale)
        .tickFormat(d3.timeFormat('%b'));
    yAxis = d3.axisLeft().scale(this.state.yScale)
        .tickFormat(d => { return `${d}℉` });

    static getDerivedStateFromProps (nextProps, prevState) {
        if (!nextProps.data) { return null } // data hasn't been loaded yet so do nothing
        const { data } = nextProps
        const { xScale, yScale, lineGenerator } = prevState

        // data has changed, so recalculate scale domains
        const timeDomain = d3.extent(data, d => { return d.date })
        const tempMax = d3.max(data, d => { return d.high })
        xScale.domain(timeDomain)
        yScale.domain([0, tempMax])

        // calculate line for lows
        lineGenerator.x(d => { return xScale(d.date) })
        lineGenerator.y(d => { return yScale(d.low) })
        const lows = lineGenerator(data)
        // and then highs
        lineGenerator.y(d => { return yScale(d.high) })
        const highs = lineGenerator(data)

        return { lows, highs }
    }

    componentDidUpdate () {
        d3.select('#lineChart #xAxis').call(this.xAxis)
        d3.select('#lineChart #yAxis').call(this.yAxis)
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (
            <svg id='lineChart' width={width} height={height}>
                <path d={this.state.highs} fill='none' stroke={red} strokeWidth='2' />
                <path d={this.state.lows} fill='none' stroke={blue} strokeWidth='2' />
                <g>
                    <g id='xAxis' transform={`translate(0, ${height - margin.bottom})`} />
                    <g id='yAxis' transform={`translate(${margin.left}, 0)`} />
                </g>
            </svg>
        )
    }
}

export default LineChart
