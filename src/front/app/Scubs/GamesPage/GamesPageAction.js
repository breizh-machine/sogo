import { CALL_API } from '../../../core/middleware/api'

export const JOIN_GAME_ACTION = 'JOIN_GAME_ACTION'
export const JOIN_GAME_ERROR = 'JOIN_GAME_ERROR'
export const JOIN_GAME_SUCCESS = 'JOIN_GAME_SUCCESS'
function joinGameAction() {
    return {
        type: JOIN_GAME_ACTION
    }
}

function fetchGameJoined(callParams) {
    return {
        [CALL_API]: {
            types: [ JOIN_GAME_SUCCESS, JOIN_GAME_ERROR ],
            callParams: callParams
        }
    }
}

export function joinGame(userId, gameId)
{
    const url = Routing.generate('scubs_api.game.join', {userId: userId, gameId: gameId});
    const method = 'put';
    const params = {};
    const callParams = { url, method, params };

    return ( dispatch ) => {
        dispatch(joinGameAction());
        return dispatch(fetchGameJoined(callParams));
    }
}




















