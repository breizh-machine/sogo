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