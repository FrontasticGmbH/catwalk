//
// Deprecated: This component is deprecated and should not be used any more
//
import React, { Component } from 'react'
import PropTypes from 'prop-types'
import {
    XYPlot,
    LineSeries,
    VerticalGridLines,
    HorizontalGridLines,
    XAxis,
    YAxis,
} from 'react-vis'
import 'react-vis/dist/style.css'

class LineChart extends Component {
    render () {
        console.info('The component ' + this.displayName + ' is deprecated – please use the Boost Theme instead: https://github.com/FrontasticGmbH/theme-boost.')

        const config = this.props.data

        const chartData = [
            { x: 0, y: 8 },
            { x: 1, y: 5 },
            { x: 2, y: 4 },
            { x: 3, y: 9 },
            { x: 4, y: 1 },
            { x: 5, y: 7 },
            { x: 6, y: 6 },
            { x: 7, y: 3 },
            { x: 8, y: 2 },
            { x: 9, y: 0 },
        ]
        return (
            <XYPlot height={300} width={300}>
                {config.showXAxis ? <XAxis /> : null}
                {config.showYAxis ? <YAxis /> : null}
                {config.showVerticalGridLines ? <VerticalGridLines /> : null}
                {config.showHorizontalGridLines ? <HorizontalGridLines /> : null}
                <LineSeries
                    data={chartData}
                    curve={config.curve}
                    style={{
                        strokeWidth: config.strokeWidth,
                    }}
                />
            </XYPlot>
        )
    }
}

LineChart.propTypes = {
    data: PropTypes.object.isRequired,
}

LineChart.defaultProps = {}

export default LineChart
