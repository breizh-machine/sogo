import { combineReducers } from 'redux'
import cubes from './CubesPickerReducer'
import gameCreation from './GameCreationReducer'
import games from './GameListReducer'
import players from './PlayersPickerReducer'
import gamesPage from './GamesPageReducer'
import gameScene from './GameView/GameSceneReducer'

const rootReducer = combineReducers({
    cubes,
    gameCreation,
    games,
    players,
    gamesPage,
    gameScene
});

export default rootReducer