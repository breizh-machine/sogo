import React, { Component, PropTypes } from 'react'
import Modal  from 'react-modal'
import { connect } from 'react-redux'

import { openGameCreationModalAction, closeGameCreationModalAction } from '../../actions/GameCreationActions'
import CubesPicker from './CubesPicker';

class GameCreation extends Component {
    constructor(props) {
        super(props);
        this.openGameCreationModal = this.openGameCreationModal.bind(this);
        this.closeGameCreationModal = this.closeGameCreationModal.bind(this);
    }

    openGameCreationModal() {
        const { dispatch } = this.props;
        dispatch(openGameCreationModalAction());
    }

    closeGameCreationModal() {
        const { dispatch } = this.props;
        dispatch(closeGameCreationModalAction());
    }

    render() {
        return  <div className="game-creation">
            <button onClick={this.openGameCreationModal}>Create a new game</button>
            <Modal isOpen={this.props.isModalOpen}>
                <button onClick={this.closeGameCreationModal}>close</button>
                <h2>Game creation modal</h2>
                <div className="cube-picker">
                    <CubesPicker user={this.props.user} />
                </div>
            </Modal>
        </div>;
    }
}

GameCreation.propTypes = {
    dispatch: PropTypes.func.isRequired,
    isModalOpen: PropTypes.bool
}

function mapStateToProps(state) {

    const isModalOpen = state.gameCreation.isModalOpen;

    return {
        isModalOpen
    }
}

export default connect(mapStateToProps)(GameCreation)

/*
'use strict'

var React = require('react');
var Modal = require('react-modal');
var TextInputRemoteResourcePicker = require('./Utils/TextInputRemoteResourcePicker');
var CubePickerList = require('./CubePickerList');

var GameCreation = React.createClass({
    displayName: 'GameCreation',

    getInitialState: function() {
        return {
            modalIsOpen: false,
            cubePickerItems: []
        };
    },

    openGameCreationModal: function() {
        this.setState({modalIsOpen: true});
    },

    closeGameCreationModal: function() {
        this.setState({modalIsOpen: false});
    },

    render: function() {
        return <div className="game-creation">
            <button onClick={this.openGameCreationModal}>Create a new game</button>
            <Modal isOpen={this.state.modalIsOpen}>
                <button onClick={this.closeGameCreationModal}>close</button>
                <h2>Game creation modal</h2>
                <div className="cube-picker">
                    <TextInputRemoteResourcePicker
                        className={'cube-picker'}
                        id={'cube-picker'}
                        placeholder={'Select a cube'}
                        value={''}
                        changeAction={this.handleInputValueChanged}
                        value={this.props.value}
                        isLoading={this.props.isLoading}
                    />
                    <div className={'picker-container'}>
                        <CubePickerList cubes={this.state.cubePickerItems}/>
                    </div>
                </div>
            </Modal>
        </div>;
    },

    handleInputValueChanged: function(value) {
        const url = Routing.generate('scubs_api.cubes_by_player', {userId: this.props.user.userId});
        this.props.fetchCubes(url, 'get', { 'q': value} );
    },

    _onCubePickerChanged: function(payload) {
        this.setState({
            cubePickerItems: payload
        });
    },

    _buildCubePickerUrl: function(value) {
        //TODO: add value
        var url = Routing.generate('scubs_api.cubes_by_player', {userId: this.props.user.userId} );
        console.log(url);
        return url;
    }
});

module.exports = GameCreation;
*/