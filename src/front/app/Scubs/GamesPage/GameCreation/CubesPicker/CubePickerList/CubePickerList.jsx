import React, { Component } from 'react'

import CubePickerListItem from './CubePickerListItem/CubePickerListItem'

class CubePickerList extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        const { handleCubeClicked, cubes } = this.props;
        return  <ul>
            {cubes.map(function(cubeItem, key) {
                return <CubePickerListItem key={key} handleCubeClicked={handleCubeClicked} cubeItem={cubeItem} />;
            })}
        </ul>;
    }
}

export default CubePickerList;