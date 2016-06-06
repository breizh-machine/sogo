'use strict'

var ReactDOM = require('react-dom');
var React = require('react');
var THREE = require('three');
var ReactTHREE = require('react-three');

var GameScene = require('./GameView/GameScene');

var renderelement = document.getElementById("scene");
ReactTHREE.render(<GameScene />, renderelement);
