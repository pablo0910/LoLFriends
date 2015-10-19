/* 
 *
 * Developed by Pablo Jimenez - pablo0910@outlook.es
 *
 * This node.js file checks periodically if a given list of Summoners are in game
 * Then it uses WebSockets in order to comunicate all this data to the client
 *
 */
var http = require("http");
var url = require("url");
var MongoClient = require('mongodb').MongoClient;
var CronJob = require('cron').CronJob; //0 0/1 * 1/1 * ? *
var express = require('express');
var router = express.Router();
var app = express();
var request = require('request');
var async = require("async");
var server;
var io;

var mongo_url = "mongodb://localhost:27017/player_data";
var API_KEY = "ed7d7d68-9555-4740-8380-82f1db0affd2";
var PLATFORM = "euw";
var mongodb;
var clientWeb;

var requestHandlers = {
	onlineData : printUsersOnline,
	addSummoner : addSummoner
}

var handle = {}
//handle["/"] = requestHandlers.iniciar;
handle["/onlineData"] = requestHandlers.onlineData;
handle["/addSummoner"] = requestHandlers.addSummoner;

//checkSummonersInGame();

runServer();

function runServer() {

	connectToDB(function() {

		function onRequest(request, response) {

		    var pathname = url.parse(request.url).pathname;
		    console.log("Petición para " + pathname + " recibida.");

		    route(handle,pathname, response, request);

		}


		server = http.createServer(onRequest);
		io = require('socket.io').listen(server);
		server.listen(8080);
		checkSummonersInGame();
		socketManager();
		console.log("Servidor Iniciado.");

	});

}

function socketManager() {

	var socket = io.listen(9876);

	socket.on('connection', function(client) {

		clientWeb = client;
		var username;

	    console.log("Client joined SOCKET");

		client.on('username', function(data) {

			username = data.user;
			client.join(username);
			        
		});


	    /*client.on('message', function(event) {
	        console.log('Mensaje recibido del cliente! ', event);
	        client.send(event);
	    });

	    client.on('chat', function(data) {
	        client.broadcast.in('laneros').emit('LALOS', data);
	        client.in('laneros').emit('LALOS', data);
	    });*/

	    client.on('disconnect', function() {
	        console.log('El cliente fue desconectado');
	    });

	});

}

function connectToDB(callback) {

	MongoClient.connect(mongo_url, function(err, db) {

	    if (err) console.log(err);
	    mongodb = db;
	    callback();

	});

}

function route(handle, pathname, response, request) {

  if (typeof handle[pathname] === 'function') {

	var url_parts = url.parse(request.url, true);
	var query = url_parts.query;
    handle[pathname](response, query);

  } else {

    console.log("No se encontro manipulador para " + pathname);
    response.writeHead(404, {"Content-Type": "text/html"});
    response.write("404 No Encontrado");
    response.end();

  }

}

function addUserToBD(nick, userName, callback) {

	nick = nick.replace(/ /g,'');
	nick = nick.toLowerCase();

	  //mongodb.usercollection.insert(body);

	var summonersData = mongodb.collection('summoner');
	var summonersFriend = mongodb.collection('summonerfriend');

	summonersData.find({ name: nick }).toArray(function (err, result) {

		if (err) {

			console.log(err);
			//Close connection

		} else if (result.length) {

			var result = "Summoner " + nick + " has already been added";
			summonersFriend.find({ user: userName, summoner: nick }).toArray(function (err, result) {

				if (err) {

					console.log(err);								
					callback(result);
					//Close connection

				} else if (result.length) {

					console.log("Summoner: %s has already been added to %s", nick, userName);
					var result = "Summoner " + nick + " has already been added to " + userName;								
					callback(result);

				} else {

					summonersFriend.insert({ user: userName, summoner: nick }, function (err, result) {

						if (err) {

						console.log(err);							
						callback("Error");

						} else {

						console.log('User %s added to %s', nick, userName);
						var result = "Summoner " + nick + " added succesfully";

						}								
						callback(result);

					});

				}

			});
			//Close connection

		} else {

			request("https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/" + nick + "?api_key=" + API_KEY, function(error, response, body) {

				if (response.statusCode == 404) {

					var result = "Summoner " + nick + " not found";
					callback(result);

				} else {

					var tempData = JSON.parse(body);
					tempData = tempData[nick];

					summonersData.insert({id: tempData.id, name: nick}, function (err, result) {

						if (err) {

						console.log(err);								
						callback("Error");

						} else {

							summonersFriend.insert({ user: userName, summoner: nick }, function (err, result) {

								if (err) {

								console.log(err);

								} else {

								console.log('User %s added to %s', nick, userName);
								var result = "Summoner " + nick + " added succesfully";

								}								
								callback(result);


							});

						}

					});

				}

			});

		}

    });

}

/*function broadcastDataToClient(client, username) {

	getAllSummonersInGameData(function (queryData) {
		console.log(username);
		client.in('colegax').emit('summoners-info', queryData.toString());

	});

}*/

function getAllSummonersInGameData(callback) {

	var summonersGame = mongodb.collection('game');

		summonersGame.find({}, {id:1, gameID:1, _id:0}).toArray(function (err, result) {

			var allSummonerGame = JSON.stringify(result);
			callback(allSummonerGame);

	});

}

function checkSummonersInGame() {

	var job = new CronJob('0 */1 * * * *', function() {

		var summonersData = mongodb.collection('summoner');
		var summonersGame = mongodb.collection('game');

		summonersData.find().toArray(function (err, result) {

			if (err) {

				console.log(err);

			} else if (result.length) {

				//console.log('Found:', result);

				async.each(result,
					  // 2nd param is the function that each item is passed to
					function(item, callback) {
					    // Call an asynchronous function, often a save() to DB
							request("https://euw.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/EUW1/" + item.id + "?api_key=" + API_KEY, function(error, response, body) {

							if (response.statusCode == 429 || response.statusCode == 403) {

								console.log("Error: %d", response.statusCode);

							} else {

								//displayResult(item, response.statusCode);
								updateSummoner(item, response.statusCode, response.body, mongodb, summonersGame);

							}
							
					    });
					},
					  // 3rd param is the function to call when everything's done
					function(err) {
					    // All tasks are done now
						console.log(err);

					}

				);

				//console.log("Summoner: %s - %d", result[i].id, statusCode);

			} else {

				console.log('No users were added!');

			}

			//Close connection

		});

	}, null, true, 'America/Los_Angeles');
	job.start();

}

function displayResult(item, statusCode) {

	console.log("Summoner: %s|%d - %d", item.name, item.id, statusCode);

}

function isSummonerInGame(statusCode) {

	if (statusCode == 200) return true;
	else return false;

}

function updateSummoner(item, statusCode, body, bd, summonersGame) {

	if (isSummonerInGame(statusCode)) var _gameID = JSON.parse(body).gameId;

	summonersGame.find({ id: item.id }).toArray(function (err, result) {

		if (err) {

			console.log(err);

		} else if (result.length) { // Check if is the same game

			if (isSummonerInGame(statusCode)) {

				if (result[0].gameID != _gameID) {

					var result = "Summoner " + item.name + " has started a game | ID: " + _gameID;
	    			broadcastSummonerStatus(result, item.name);
					updateUserGame(summonersGame, item.id, _gameID);

				} else {

					console.log('User %s is still in the same game', item.name);

				}

			} else {


				var result = "Summoner " + item.name + " is not in game anymore";
	    		broadcastSummonerStatus(result, item.name);
				summonersGame.remove({ id: item.id });
				console.log('User %s is not in Game', item.name);

			}

		} else { // First user game

			if (isSummonerInGame(statusCode)) {

				var result = "Summoner " + item.name + " has started a game | ID: " + _gameID;
	    		broadcastSummonerStatus(result, item.name);
				insertUserGame(summonersGame, item.id, _gameID);

			} else {

				console.log('User %s is not in Game', item.name);

			}

		}

	});

}

function broadcastSummonerStatus(message, summonerName) {

	var summonersFriend = mongodb.collection('summonerfriend');

	summonersFriend.find({ summoner: summonerName }).toArray(function (err, result) {

		if (err) {

			console.log(err);

		} else if (result.length) { 

			for (var i=0; i < result.length; i++) {

				io.sockets.in(result[i].user).emit('summoners-info', message);

			}

		} else { 

			

		}

	});

}

function insertUserGame(summonersGame, idSummoner, idGame) {

	summonersGame.insert({id: idSummoner, gameID: idGame}, function (err, result) {

		if (err) {

		console.log(err);

		} else {

		console.log('User %d | Game %d added', idSummoner, idGame);

		}

	});

}

function updateUserGame(summonersGame, idSummoner, idGame) {

	summonersGame.update({id: idSummoner}, { id: idSummoner, gameID: idGame }, function (err, result) {

		if (err) {

		console.log(err);

		} else {

		console.log('User %d | Game %d updated', idSummoner, idGame);

		}

	});

}

function printUsersOnline(response, data) {

	var summonersGame = mongodb.collection('game');
	summonersGame.find({}, {id:1, gameID:1, _id:0}).toArray(function (err, result) {

		if (err) {

			console.log(err);

		} else if (result.length) { // Check if is the same game

			var json = JSON.stringify(result);

			response.writeHead(200, {"Content-Type": "text/html"});
			response.write(json);
			response.end();

		}

	});

}

function addSummoner(response, data) {
	console.log("Adding: %s to %s", data.nick, data.user);

	addUserToBD(data.nick, data.user, function(result) {

		response.writeHead(200, {"Content-Type": "text/html"});
		response.write(result);
		response.end();

	});

}
