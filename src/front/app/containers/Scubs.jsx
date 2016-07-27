import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import { GAMES_PAGE_STATE, PLAY_PAGE_STATE } from './Pages';
import GamesPage from './GamesPage';
import PlayPage from './PlayPage';

class Scubs extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        const { sc, user } = this.props;
        sc.subscribe('gameCreation'+user.id, function(topic, data) {
            console.log('New game created2 "' + topic + '" : ', data);
        });
    }

    _renderApp() {
        const { entities, user } = this.props;
        switch (this.props.appState) {
            case GAMES_PAGE_STATE:
                return <GamesPage user={user} entities={entities} />;
            case PLAY_PAGE_STATE:
                return <PlayPage user={user} currentGame={this.props.currentGame}/>;
            default:
                return <div>
                    Unknown state
                </div>
        }
    }

    render() {
        return this._renderApp();
    }
}

Scubs.propTypes = {
    appState: PropTypes.string,
    user: PropTypes.object.isRequired
}

export default Scubs

