#Scubs app state structure
    {
        "appState": "state",
        "gamesPage": {
            "isFetching": false,
            "items": [],
            "displayJoinGameError": false,
            "joinGameError": {
                "code": 0,
                "message": ""
            },
            "joinGameLoading": false,
            "joinGameSucceeded": false,
            "gameCreation": {
                "betValue": 10,
                "creationError": {
                    "code": 0,
                    "message": ""
                },
                "creationLoading": false,
                "displayCreationErrorMessage": false,
                "guest": "",
                "isModalOpen": false,
                "localCubeId": ""
            }
        },
        "playPage": {
            "gameScene": {
                "cameraMatrix": {
                    "elements": {
                        "0": 1,
                        "1": 0,
                        "10": 1,
                        "11": 0,
                        "12": 0,
                        "13": 0,
                        "14": 0,
                        "15": 1,
                        "2": 0,
                        "3": 0,
                        "4": 0,
                        "5": 1,
                        "6": 0,
                        "7": 0,
                        "8": 0,
                        "9": 0
                    }
                },
                "cameraRotationY": 0,
                "cursorPosition": {
                    "x": 0,
                    "y": 4,
                    "z": 0
                },
                "displayPlayTurnErrorMessage": false,
                "game": {},
                "height": 600,
                "playTurnError": {},
                "playTurnLoading": false,
                "rotationImpulse": 0,
                "width": 800
            }
        },
        "entities": {
            "games": {
                "ab0ad536-c0b8-4300-8ef0-170747fae919": {
                    "bet": 10,
                    "cubeWonThumbnail": "string",
                    "gameStartDate": "string",
                    "hasVisitorDeclined": false,
                    "hasVisitorJoined": false,
                    "id": "string",
                    "joinable": false,
                    "lastTurnPlayedDate": "string",
                    "lost": false,
                    "nbTurnsPlayed": 0,
                    "opponentName": "string",
                    "opponentProfilePicture": "string",
                    "playable": false,
                    "won": false
                }
            },
            "cubes": {
                "8bc905c1-40f3-4df6-a9c1-bd45232eb278": {
                    "description": "string",
                    "id": "string",
                    "rarity": 0,
                    "textureUrl": "string",
                    "thumbnailUrl": "string"
                }
            },
            "players": {
                "8bc905c1-40f3-4df6-a9c1-bd45232eb278": {
                    "id": "string",
                    "profile_picture": "string",
                     "username": "string"
                }
            }
        },
        "user": {
            "credits": 10,
            "id": "",
            "profilePicture": "",
            "username": ""
        },
    }

