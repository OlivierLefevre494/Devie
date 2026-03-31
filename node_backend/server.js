// Libraries
const WebSocket = require("ws");
const fs = require('fs');
const https = require('https');
const h = require("./helper.js");

//https server
const server = https.createServer({
  cert: fs.readFileSync('cert.pem'),
  key: fs.readFileSync('key.pem')
});

server.listen(8080);

// WebSocket Server
var wss = new WebSocket.Server(/*{ server }*/ {port: 8081}), clients = new Array();
wss.on('connection', function (ws) {
  // Connection Settings
  let logged_in = false;
  let userdata = {};

  // Message received
  ws.on('message', function (message) {
    try {
      // Check if message is a valid JSON string
      if (!h.json_validate(message)) return;

      // Parse JSON string
      let data = JSON.parse(message);

      // First message from client contains user login data
      if (!logged_in && data["action"] == "login") {
        // Debug
        console.log("Login attempt with token",data["token"],"at",new Date().toString());

        // Get user data
        sql = "SELECT * FROM `users` WHERE token="+h.db.escape(data["token"]);
        h.db.query(sql, function (err, results, fields) {
          // Check if user exists
          if (results.length < 1) return;

          // Send login message
          ws.send("logged_in");

          // Save user data
          userdata.username = results[0].username,
          userdata.avatar = results[0].avatar,
          userdata.token = results[0].token;
          userdata.id = results[0].id;

          // Add client to active clients array
          clients.push({ws:ws, userdata:userdata});

          // Send back all the groups the client is in
          sql = "SELECT * FROM `groups` WHERE groups_id IN (SELECT groups_id FROM `groups_members` WHERE user_id="+h.db.escape(userdata.id)+")";
          h.db.query(sql, function(err, results, fields) {
            if (err) throw err;
            for (grp of results) {
              owner = grp.owner_id == userdata.id;
              ws.send(JSON.stringify({
                action: "grp",
                id: grp.groups_id,
                name: grp.group_name,
                avatar: grp.group_avatar,
                bio: grp.groupbio,
                owner: owner
              }));
            }
          });

          // End function and make sure this doesn't run again
          first_message = false;
          return;
        });
      }

      else {
        // Determine the requested action from client
        switch(data["action"]) {
          // Get messages from chat
          case "get_msgs":
            // Check if user in requested chat
            sql = "SELECT user_id FROM `groups_members` WHERE user_id="+h.db.escape(userdata.id)+" AND groups_id="+h.db.escape(data["chat_id"]);
            h.db.query(sql, function (err, results, fields) {
              // Less than one row, user not in chat
              if (results.length < 1) return;

              // Get messages from chat
              sql = "SELECT groups_contents.groups_id, users.username, users.avatar, groups_contents.date_time, groups_contents.message FROM `groups_contents` INNER JOIN `users` ON users.id=groups_contents.user_id WHERE groups_contents.groups_id="+h.db.escape(data["chat_id"])+" ORDER BY groups_contents.date_time";
              h.db.query(sql, function (err, results, fields) {
                // No messages in group, tell user to send
                if (results.length < 1) {
                  ws.send(JSON.stringify({
                    action: "msg",
                    groups_id: data["chat_id"],
                    username: "Devie",
                    avatar: "../images/blankprofilepicture.png",
                    message: "Be the first to send a message!",
                    date_time: new Date().toString()
                  }));
                  return;
                }

                // Send messages
                for (msg of results) {
                  msg.action = "msg";
                  ws.send(JSON.stringify(msg));
                }
              });
            });

            break;

          // Send message
          case "msg":
            // Check if user in requested chat
            sql = "SELECT user_id FROM `groups_members` WHERE user_id="+h.db.escape(userdata.id)+" AND groups_id="+h.db.escape(data["chat_id"]);
            h.db.query(sql, function (err, results, fields) {
              // Less than one row, user not in chat
              if (results.length < 1) return;

              // Insert new message
              sql = "INSERT INTO `groups_contents` (user_id, groups_id, message, filetype, date_time) VALUES ("+h.db.escape(userdata.id)+","+h.db.escape(data["chat_id"])+","+h.db.escape(data["content"])+",'text',NOW())";
              h.db.query(sql, function (err, results, fields) {
                // Errors
                if (err) throw err;

                // Get the id of all users in chat
                let chat_users = Array();
                sql = "SELECT user_id FROM `groups_members` WHERE groups_id="+h.db.escape(data["chat_id"]);
                h.db.query(sql, function (err, results, fields) {
                  // Push ids into array
                  for (r of results) {
                    chat_users.push(r["user_id"]);
                  }

                  // Loop through connected clients, send message if client id in chat members array
                  for (client of clients) {
                    client.ws.send(JSON.stringify({
                      action: "msg",
                      groups_id: data["chat_id"],
                      username: userdata.username,
                      avatar: userdata.avatar,
                      date_time: new Date(new Date().toUTCString()),
                      message: data["content"]
                    }));
                  }
                });
              });
            });

            break;

          // Add member to group
          case "add_to_grp":
            // Check if user is tryna add themselves
            if (data["new_user"] == userdata.username) {
              ws.send(JSON.stringify({
                action: "error",
                message: "You can't add yourself to your own group"
              }));
              return;
            }

            // Check if user is owner of group
            sql = "SELECT owner_id FROM `groups` WHERE groups_id="+h.db.escape(data["chat_id"]);
            h.db.query(sql, function (err, results, fields) {
              if (err) throw err;
              // User not owner
              if (results[0].owner_id != userdata.id) return;

              // Get new member id
              sql = "SELECT id FROM `users` WHERE username="+h.db.escape(data["new_user"]);
              h.db.query(sql, function(err, results, fields) {
                if (err) throw err;

                // User doesn't exist
                if (results.length < 1) {
                  ws.send(JSON.stringify({
                    action: "error",
                    message: "That user doesn't exist"
                  }));
                  return;
                }

                // User id
                user_id = results[0].id;

                // Check if new member is already part of the group
                sql = "SELECT * FROM `groups_members` WHERE groups_id="+h.db.escape(data["chat_id"])+" AND user_id="+user_id;
                h.db.query(sql, function (err, results, fields) {
                  if (err) throw err;
                  // If more than 0 row is found, member is already in group
                  if (results.length > 0) {
                    ws.send(JSON.stringify({
                      action: "error",
                      message: "This user is already part of that group"
                    }));
                    return;
                  }

                  // Insert new user into `groups_members`
                  sql = "INSERT INTO `groups_members` (jointime, user_id, groups_id) VALUES (NOW(),"+user_id+","+h.db.escape(data["chat_id"])+")";
                  h.db.query(sql, function (err, results, fields) {
                    // Query group info
                    sql = "SELECT * FROM `groups` WHERE groups_id="+h.db.escape(data["chat_id"]);
                    h.db.query(sql, function (err, results, fields) {
                      // Confirm user added
                      ws.send(JSON.stringify({
                        action: "success",
                        message: "Successfully added "+data["new_user"]+" to your group"
                      }));

                      // Send group info to added user
                      for (client of clients) {
                        if (client.userdata.id == user_id) {
                          client.ws.send(JSON.stringify({
                            action: "grp",
                            id: results[0].groups_id,
                            name: results[0].group_name,
                            avatar: results[0].group_avatar,
                            bio: results[0].groupbio
                          }));
                          return;
                        }
                      }
                    });
                  });
                });
              });
            });

            break;

          // Create new group
          case "new_grp":
            sql = "INSERT INTO `groups` (owner_id, group_name, group_avatar, groupbio) VALUES ("+h.db.escape(userdata.id)+","+h.db.escape(data["content"])+",'../images/blankprofilepicture.png','just a group')";
            h.db.query(sql, function (err, results, fields) {
              // Get new group info
              sql = "SELECT * FROM `groups` WHERE owner_id="+h.db.escape(userdata.id)+" AND group_name="+h.db.escape(data["content"]);
              h.db.query(sql, function (err, results, fields) {
                group_id = results[0].groups_id;
                group_bio = results[0].bio;
                group_avatar = results[0].group_avatar;
                group_name = results[0].group_name;

                // Insert User into new group
                sql = "INSERT INTO `groups_members` (jointime, groups_id, user_id) VALUES (NOW(),"+group_id+","+h.db.escape(userdata.id)+")";
                h.db.query(sql, function(err, results, fields) {
                  if (err) throw err;
                  ws.send(JSON.stringify({
                    action: "grp",
                    id: group_id,
                    name: group_name,
                    avatar: group_avatar,
                    bio: group_bio,
                    owner: true
                  }));
                });
              });
            });
            break;

          // Get friends info
          case "friends":
            // Get all friends
            sql = "SELECT id, username, avatar FROM `users` WHERE id IN (SELECT user_two FROM `friends` WHERE user_one="+h.db.escape(userdata.id)+") OR id IN (SELECT user_one FROM `friends` WHERE user_two="+h.db.escape(userdata.id)+")"
            h.db.query(sql, function (err, results, fields) {
              for(row of results) {
                ws.send(JSON.stringify({
                  action: "friend",
                  type: "all",
                  user_id: row.id,
                  username: row.username,
                  avatar: row.avatar
                }));
              }
            });

            // Incoming friends
            sql = "SELECT id, username, avatar FROM `users` WHERE id IN (SELECT sender FROM `friend_request` WHERE receiver="+h.db.escape(userdata.id)+")"
            h.db.query(sql, function (err, results, fields) {
              for(row of results) {
                ws.send(JSON.stringify({
                  action: "friend",
                  type: "inc",
                  user_id: row.id,
                  username: row.username,
                  avatar: row.avatar
                }));
              }
            });

            // Pending requests
            sql = "SELECT id, username, avatar FROM `users` WHERE id IN (SELECT receiver FROM `friend_request` WHERE sender="+h.db.escape(userdata.id)+")"
            h.db.query(sql, function (err, results, fields) {
              for(row of results) {
                ws.send(JSON.stringify({
                  action: "friend",
                  type: "pending",
                  user_id: row.id,
                  username: row.username,
                  avatar: row.avatar
                }));
              }
            });

            break;

          // Send friend request
          case "friend_request":
            // Check if friend request to self
            if (data['username'] == userdata.username) {
              ws.send(JSON.stringify({
                action: "error",
                message: "Can't send a friend request to yourself ;)"
              }));
              return;
            }

            // Check if user exists
            sql = "SELECT id FROM `users` WHERE username="+h.db.escape(data['username']);
            h.db.query(sql, function(err, results, fields) {
              // If no rows are returned, user doesn't exist
              if (results.length < 1) {
                ws.send(JSON.stringify({
                  action: "error",
                  message: "Couldn't find a user with username \""+data['username']+"\""
                }));
                return;
              }

              // Friend id
              friend_id = results[0].id;

              // Check if friend request already pending
              sql = "SELECT * FROM `friend_request` WHERE sender="+h.db.escape(userdata.id)+" AND receiver="+h.db.escape(friend_id);
              h.db.query(sql, function (err, results, fields) {
                // If result length > 0, friend request is already pending
                if (results.length > 0) {
                  ws.send(JSON.stringify({
                    action: "error",
                    message: "Already sent a friend request to that user"
                  }));
                  return;
                }

                // Insert friend request into db
                sql = "INSERT INTO `friend_request` (sender, receiver) VALUES ("+h.db.escape(userdata.id)+","+h.db.escape(friend_id)+")";
                h.db.query(sql, function(err, results, fields) {
                  if (err) throw err;
                  // Send friend request to client
                  sql = "SELECT * FROM `users` WHERE id="+h.db.escape(friend_id);
                  h.db.query(sql, function (err, results, fields) {
                    row = results[0];

                    // Pending request back to client
                    ws.send(JSON.stringify({
                      action: "friend",
                      type: "pending",
                      user_id: row.id,
                      username: row.username,
                      avatar: row.avatar
                    }));

                    // Confirm Success
                    ws.send(JSON.stringify({
                      action: "success",
                      message: "Friend request sent!"
                    }));

                    // Friend request to receiver (if connected)
                    for (client of clients) {
                      if (client.userdata.id == row.id) {
                        ws.send(JSON.stringify({
                          action: "friend",
                          type: "request",
                          user_id: userdata.id,
                          username: userdata.username,
                          avatar: userdata.avatar
                        }));
                      }
                    }
                  });
                });
              });
            });

            break;

          // Accept friend request
          case "start_friendship":
            // Check if friend request pending
            sql = "SELECT * FROM `friend_request` WHERE sender="+h.db.escape(data["user_id"]);
            h.db.query(sql, function (err, results, fields) {
              if (err) throw err;

              // If result length > 0, friend request is pending
              if (results.length > 0) {
                let user_one = h.db.escape(results[0].sender);
                let user_two = h.db.escape(userdata.id);
                // Insert friend in database
                sql = "INSERT INTO `friends` (user_one, user_two) VALUES ("+user_one+","+user_two+")";
                h.db.query(sql, function (err, results, fields) {
                  // Remove pending friend request
                  sql = "DELETE FROM `friend_request` WHERE sender="+user_one+" AND receiver="+user_two;
                  h.db.query(sql, function (){
                    // Send new friend to client
                    sql = "SELECT username, avatar FROM `users` WHERE id="+user_one;
                    h.db.query(sql, function(err, results, fields) {
                      ws.send(JSON.stringify({
                        action: "friend",
                        type: "all",
                        user_id: user_one,
                        username: results[0].username,
                        avatar: results[0].avatar
                      }));
                    })
                  });
                });
              }
            });
            break;

          // Remove friend / Decline friend request
          case "reject_friend":
            sql = "DELETE FROM `friends` WHERE user_one IN ("+h.db.escape(userdata.id)+","+h.db.escape(data["user_id"])+") AND user_two IN ("+h.db.escape(userdata.id)+","+h.db.escape(data["user_id"])+")";
            h.db.query(sql);
            sql = "DELETE FROM `friend_request` WHERE receiver="+h.db.escape(userdata.id)+" AND sender="+h.db.escape(data["user_id"]);
            h.db.query(sql);

          // Get avatar
          case "avatar":
            ws.send(JSON.stringify({action:"avatar", url:userdata.avatar}));
            break;
        }
      }
    } catch (error) {
      console.error(error);
    }
  });
});

// Handle errors
process.on('uncaughtException', error => {
  console.error(error);
})
