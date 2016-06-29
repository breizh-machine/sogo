import React, { Component } from 'react'
import { If, Then } from 'react-if';

class CubePickerListItem extends Component {

    constructor(props) {
        super(props);
        this.onClicked = this.onClicked.bind(this);
    }

    render() {
        return  <li style={{'display': 'inline-block'}} className={this.props.cubeItem.selected ? 'cube-picker-item selected' : 'cube-picker-item'} onClick={this.onClicked}>
            <img src={this.props.cubeItem.thumbnail_url} width={'75'} height={'75'} />
            <div className={'description'}>{this.props.cubeItem.description}</div>
            <If condition={ this.props.cubeItem.selected === true}>
                <Then>
                    <div>selected</div>
                </Then>
            </If>
        </li>;
    }

    onClicked() {
        this.props.handleCubeClicked(this.props.cubeItem.id);
    }
};

export default CubePickerListItem;
