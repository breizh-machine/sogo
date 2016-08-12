import React, { Component, PropTypes } from 'react'
import { connect } from 'react-redux'

import { GAMES_PAGE_STATE, PLAY_PAGE_STATE } from './states';
import GamesPage from './GamesPage/GamesPage';
import PlayPage from './PlayPage/PlayPage';
import { updateGames, initEntities } from './Entities/EntitiesActions';

class Scubs extends Component {
    constructor(props) {
        super(props);
    }

    componentWillMount() {
        const { sc, user, dispatch, initialEntities } = this.props;
        switch (this.props.appState) {
            case GAMES_PAGE_STATE:
                dispatch(initEntities(initialEntities));
                sc.subscribe('GAMES_CHANNEL'+user.id, function(topic, msg) {
                    console.log('dispatching game : ', topic, msg);
                    switch (msg.type)
                    {
                        case 'GAME_CREATED_TYPE':
                        case 'GAME_INVITATION_TYPE':
                            dispatch(updateGames(msg.data));
                            break;
                    }
                });
                break;
            case PLAY_PAGE_STATE:
            default:
                break;
        }
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

function mapStateToProps(state) {
    const entities = state.entities;
    
    return {
        entities
    }
}

export default connect(mapStateToProps)(Scubs)

