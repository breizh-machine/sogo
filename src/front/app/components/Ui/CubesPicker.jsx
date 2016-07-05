import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'
import { selectCube, loadCubes } from '../../actions/CubesPickerActions'
import TextInputRemoteResourcePicker from './Utils/TextInputRemoteResourcePicker';
import CubePickerList from './CubePickerList';

class CubesPicker extends Component {
    constructor(props) {
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.handleCubeClicked = this.handleCubeClicked.bind(this);
    }

    componentDidMount() {
        const { dispatch } = this.props;
        dispatch(loadCubes(this.props.user.id))
    }

    handleCubeClicked(cubeId) {
        this.props.dispatch(selectCube(cubeId));
    }

    handleChange(e) {
        e.preventDefault();
        const { dispatch } = this.props
        dispatch(loadCubes(this.props.user.id, e.target.value));
    }

    render() {
        const { cubes } = this.props;
        return  <div className="cube-picker">
            <TextInputRemoteResourcePicker
                className={'cube-picker'}
                id={'cube-picker'}
                placeholder={'Select a cube'}
                value={this.props.cubes.cubesPickerInputValue}
                changeAction={this.handleChange}
                isLoading={this.props.cubes.isFetching}
            />
            <div className={'picker-container'}>
                <CubePickerList cubes={cubes.items} handleCubeClicked={this.handleCubeClicked}/>
            </div>
        </div>;
    }
}

CubesPicker.propTypes = {
    cubes: PropTypes.object,
    dispatch: PropTypes.func.isRequired
}

function mapStateToProps(state) {
    const cubes = state.cubes;

    return {
        cubes
    }
}

export default connect(mapStateToProps)(CubesPicker)