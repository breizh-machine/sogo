'use strict'

var React = require('react');

var GameCreation = React.createClass({
    displayName: 'GameCreation',
    render: function() {
        return <div className="game-creation">
            <label for="create-game">Create a new game</label>
            <input type="text" minlength="40" name="create-game"/>
        </div>;
    }
});

module.exports = GameCreation;