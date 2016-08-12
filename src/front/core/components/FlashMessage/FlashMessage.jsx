import React, { Component, PropTypes } from 'react'
import { If, Then } from 'react-if';

class FlashMessage extends Component {

    constructor(props) {
        super(props);
        this.onCloseClicked = this.onCloseClicked.bind(this);
    }

    onCloseClicked() {
        this.props.closeHandler();
    }

    render() {
        return <If condition={this.props.display}>
            <Then>
                <div className={ 'flash-message ' + this.props.type }>
                    <div className="close" onClick={this.onCloseClicked}>X</div>
                    <div className="message">{this.props.message}</div>
                </div>
            </Then>
        </If>
    }
}

FlashMessage.propTypes = {
    display: PropTypes.bool,
    type: PropTypes.string,
    message: PropTypes.string,
    closeHandler: PropTypes.func.isRequired
}

export default FlashMessage