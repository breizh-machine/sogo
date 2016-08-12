import { CALL_API } from '../../../../../core/middleware/api'

export const SELECT_PLAYER = 'SELECT_PLAYER'
export function selectPlayer(playerId) {
    return {
        type: SELECT_PLAYER,
        selectedPlayerId: playerId
    }
}

export const PLAYERS_FAILURE = 'PLAYERS_FAILURE'
export const PLAYERS_REQUEST= 'PLAYERS_REQUEST'
export const PLAYERS_SUCCESS= 'PLAYERS_SUCCESS'
export function requestPlayers(q) {
    return {
        type: PLAYERS_REQUEST,
        playersPickerInputValue: q
    }
}

function fetchPlayers(callParams) {
    return {
        [CALL_API]: {
            types: [ PLAYERS_SUCCESS, PLAYERS_FAILURE ],
            callParams: callParams
        }
    }
}
export function loadPlayers(userId, q = '')
{
    const url = Routing.generate('scubs_api.players_by_user', {userId: userId, _format: 'json', q: q});
    const method = 'get';
    const params = [];
    const callParams = { url, method, params };

    return ( dispatch ) => {
        if (!q) {
            return dispatch(requestPlayers(q));
        } else {
            dispatch(requestPlayers(q));
            return dispatch(fetchPlayers(callParams));
        }
    }
}