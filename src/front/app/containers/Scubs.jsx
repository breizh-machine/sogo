import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import { GAMES_PAGE_STATE, PLAY_PAGE_STATE } from './Pages';
import GamesPage from './GamesPage';

class Scubs extends Component {
    constructor(props) {
        super(props);
    }

    _renderApp() {
        switch (this.props.appState) {
            case GAMES_PAGE_STATE:
                return <GamesPage user={this.props.user}/>;
            case PLAY_PAGE_STATE:
                return <div>
                    Play state
                </div>;
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

