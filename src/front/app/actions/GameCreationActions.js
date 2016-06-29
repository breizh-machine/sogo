import { CALL_API } from '../middleware/api'

export const OPEN_GAME_CREATION_MODAL = 'OPEN_GAME_CREATION_MODAL'
export function openGameCreationModalAction() {
    return {
        type: OPEN_GAME_CREATION_MODAL
    }
}


export const CLOSE_GAME_CREATION_MODAL = 'CLOSE_GAME_CREATION_MODAL'
export function closeGameCreationModalAction() {
    return {
        type: CLOSE_GAME_CREATION_MODAL
    }
}

export const SET_BET_ACTION = 'SET_BET_ACTION'
export function setBetAction(bet) {
    //TODO
    const minBetValue = 10;
    const value = bet ? Math.max(minBetValue, parseInt(bet)) : minBetValue;

    return {
        type: SET_BET_ACTION,
        bet: value
    }
}

export const CREATE_GAME_ACTION = 'CREATE_GAME_ACTION'
export const CREATE_GAME_ERROR = 'CREATE_GAME_ERROR'
export const CREATE_GAME_SUCCESS = 'CREATE_GAME_SUCCESS'
function createGameAction() {
    return {
        type: CREATE_GAME_ACTION
    }
}

function fetchGameCreation(callParams) {
    return {
        [CALL_API]: {
            types: [ CREATE_GAME_SUCCESS, CREATE_GAME_ERROR ],
            callParams: callParams
        }
    }
}

export function createGame(userId, localCubeId, bet, guest = null)
{
    const url = Routing.generate('scubs_api.game.new', {userId: userId});
    const method = 'post';
    let params = {'localCubeId': localCubeId, 'bet': bet};
    if (guest && guest !== null) {
        params.guest = guest;
    }
    const callParams = { url, method, params };

    return ( dispatch ) => {
        dispatch(createGameAction());
        return dispatch(fetchGameCreation(callParams));
    }
}




















