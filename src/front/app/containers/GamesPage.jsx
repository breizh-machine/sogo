import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import GameCreation from '../components/Ui/GameCreation';
import GameList from '../components/Ui/GameList';

class GamesPage extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return  <div className="games-page">
            <GameCreation user={this.props.user} />
            <GameList user={this.props.user} />
        </div>;
    }
}
export default GamesPage
