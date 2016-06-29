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
export function createGameAction(userId, localCubeId, bet, guest = null) {
    return {
        [CALL_API]: {
            type: CREATE_GAME_ACTION,
            userId: userId,
            localCubeId: localCubeId,
            bet: bet,
            guest: guest
        }
    }
}




