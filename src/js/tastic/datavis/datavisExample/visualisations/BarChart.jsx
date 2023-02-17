//
// Deprecated: This component is deprecated and should not be used any more
//
import { deprecate } from '@frontastic/common'
import React, { Component } from 'react'
import * as d3 from 'd3'
import chroma from 'chroma-js'

const width = 650
const height = 400
const margin = { top: 20, right: 5, bottom: 20, left: 35 }
const red = '#eb6a5b'
const green = '#b6e86f'
const blue = '#52b6ca'
const colors = chroma.scale([blue, green, red]).mode('hsl')

class BarChart extends Component {
    state = {
        bars: [], // array of rects
        // d3 helpers
        xScale: d3.scaleTime().range([margin.left, width - margin.right]),
        yScale: d3.scaleLinear().range([height - margin.bottom, margin.top]),
        colorScale: d3.scaleLinear(),
    };

    xAxis = d3
        .axisBottom()
        .scale(this.state.xScale)
        .tickFormat(d3.timeFormat('%b'));
    yAxis = d3
        .axisLeft()
        .scale(this.state.yScale)
        .tickFormat(d => { return `${d}℉` });

    static getDerivedStateFromProps (nextProps, prevState) {
        if (!nextProps.data) { return null } // data hasn't been loaded yet so do nothing
        const { data } = nextProps
        const { xScale, yScale, colorScale } = prevState

        // data has changed, so recalculate scale domains
        const timeDomain = d3.extent(data, d => { return d.date })
        const tempMax = d3.max(data, d => { return d.high })
        const colorDomain = d3.extent(data, d => { return d.avg })
        xScale.domain(timeDomain)
        yScale.domain([0, tempMax])
        colorScale.domain(colorDomain)

        // calculate x and y for each rectangle
        const bars = data.map(d => {
            const y1 = yScale(d.high)
            const y2 = yScale(d.low)
            return {
                x: xScale(d.date),
                y: y1,
                height: y2 - y1,
                fill: colors(colorScale(d.avg)),
            }
        })

        return { bars }
    }

    componentDidUpdate () {
        d3.select('#barChart #xAxis').call(this.xAxis)
        d3.select('#barChart #yAxis').call(this.yAxis)
    }

    render () {
        deprecate('This component is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.', this)

        return (
            <svg id='barChart' width={width} height={height}>
                {this.state.bars.map((d, i) => {
 return (
     <rect
         key={i}
         x={d.x}
         y={d.y}
         width='2'
         height={d.height}
         fill={d.fill}
                    />
 )
})}
                <g>
                    <g
                        id='xAxis'
                        transform={`translate(0, ${height - margin.bottom})`}
                    />
                    <g id='yAxis' transform={`translate(${margin.left}, 0)`} />
                </g>
            </svg>
        )
    }
}

export default BarChart
