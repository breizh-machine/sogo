'use strict'

import React, { Component } from 'react'
import CubePickerListItem from './CubePickerListItem'

class CubePickerList extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        const clickHandler = this.props.handleCubeClicked;
        return  <ul>
            {this.props.cubes.map(function(cubeItem, key) {
                return <CubePickerListItem key={key} handleCubeClicked={clickHandler} cubeItem={cubeItem} />;
            })}
        </ul>;
    }
}

export default CubePickerList;