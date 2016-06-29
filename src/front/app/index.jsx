import 'babel-polyfill'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import GamesPage from './containers/GamesPage'
import configureStore from './stores/configureStore'

const store = configureStore();

render(
    <Provider store={store}>
        <GamesPage user={ {userId: 'a7e8c0d3-9e54-43de-a1bb-307bd428073b'} }/>
    </Provider>,
    document.getElementById('games-page-container')
)

