import 'babel-polyfill'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import GameCreation from './components/Ui/GameCreation'
import configureStore from './stores/configureStore'

const store = configureStore();

render(
    <Provider store={store}>
        <GameCreation user={ {userId: 'a7e8c0d3-9e54-43de-a1bb-307bd428073b'} }/>
    </Provider>,
    document.getElementById('game-lobby-container')
)

