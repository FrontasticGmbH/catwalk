import React, {Component} from 'react';

const asyncComponent = (importComponent, height = 0) => {
    return class extends Component {
        state = {
            component: null
        }

        componentDidMount() {
            importComponent()
                .then(component => {
                    this.setState({component: component.default});
                });
        }

        render() {
            const InnerComponent = this.state.component;

            if (!InnerComponent) {
                return <div className='c-asyc-component' style={{ height: height + 'px' }} />
            }

            return <InnerComponent {...this.props}/>;
        }
    }
};

export default asyncComponent;
