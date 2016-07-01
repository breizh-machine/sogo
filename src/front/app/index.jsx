import 'babel-polyfill'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import Scubs from './containers/Scubs'
import { GAMES_PAGE_STATE, PLAY_PAGE_STATE } from './containers/Pages';
import configureStore from './stores/configureStore'

const store = configureStore();
window.store = store;
window.Provider = Provider;
window.Scubs = Scubs;
window.render = render;
window.React = React;


