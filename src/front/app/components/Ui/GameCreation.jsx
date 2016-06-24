'use strict'

var React = require('react');
var Modal = require('react-modal');

var GameCreation = React.createClass({
    displayName: 'GameCreation',

    getInitialState: function() {
        return { modalIsOpen: false };
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
                <div>Game creation modal</div>
            </Modal>
        </div>;
    }
});

module.exports = GameCreation;