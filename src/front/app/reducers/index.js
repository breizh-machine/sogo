import { combineReducers } from 'redux'
import cubes from './CubesPickerReducer'
import gameCreation from './GameCreationReducer'
import games from './GameListReducer'
import players from './PlayersPickerReducer'
import gamesPage from './GamesPageReducer'
import scubs from './EntitiesReducer'
import gameScene from './GameView/GameSceneReducer'

const rootReducer = combineReducers({
    cubes,
    gameCreation,
    games,
    players,
    gamesPage,
    gameScene,
    scubs
});

export default rootReducer