import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import { selectCube, loadCubes } from './CubesPickerActions'
import TextInputRemoteResourcePicker from '../../../../../core/components/TextInputRemoteResourcePicker/TextInputRemoteResourcePicker';
import CubePickerList from './CubePickerList/CubePickerList';

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
        const { cubesPicker } = this.props;
        return  <div className="cube-picker">
            <TextInputRemoteResourcePicker
                className={'cube-picker'}
                id={'cube-picker'}
                placeholder={'Select a cube'}
                value={cubesPicker.cubesPickerInputValue}
                changeAction={this.handleChange}
                isLoading={cubesPicker.isFetching}
            />
            <div className={'picker-container'}>
                <CubePickerList cubes={cubesPicker.items} handleCubeClicked={this.handleCubeClicked}/>
            </div>
        </div>;
    }
}

CubesPicker.propTypes = {
    cubes: PropTypes.object,
    dispatch: PropTypes.func.isRequired
}

function mapStateToProps(state) {
    const cubesPicker = state.cubesPicker;
    //TODO use cubes from entities
    return {
        cubesPicker
    }
}

export default connect(mapStateToProps)(CubesPicker)