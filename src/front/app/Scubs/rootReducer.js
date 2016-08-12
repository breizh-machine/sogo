import { combineReducers } from 'redux'
import gameCreation from './GamesPage/GameCreation/GameCreationReducer'
import gamesPage from './GamesPage/GamesPageReducer'
import entities from './Entities/EntitiesReducer'
import gameScene from './PlayPage/GameScene/GameSceneReducer'
import cubesPicker from './GamesPage/GameCreation/CubesPicker/CubesPickerReducer'
import playersPicker from './GamesPage/GameCreation/PlayersPicker/PlayersPickerReducer'

/*
const rootReducer = combineReducers({
    'scubs': {
        entities,
        'gamesPage': {
            gamesPage,
            'gameCreation': {
                gameCreation
            }
        },
        'playPage': {
            'gameScene': {
                gameScene
            }
        }
    }
});
*/

const rootReducer = combineReducers({
    entities,
    gamesPage,
    gameCreation,
    gameScene,
    cubesPicker,
    playersPicker
});



export default rootReducer