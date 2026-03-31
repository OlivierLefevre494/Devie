// Libraries
const mysql = require("mysql");

// Connect to database and handle errors
var db;
function db_connect() {
    console.log("Connecting to database");
    // Database settings
    /*
    var db_settings = {
        host     : 'mysql.thedevie.com',
        user     : 'devie',
        password : 'month18fr\$eedom',
        database : 'devie'
    }*/
    var db_settings = {
        host     : 'localhost',
        user     : 'root',
        password : '',
        database : 'devie'
    };

    // Connect
    db = mysql.createConnection(db_settings);
    db.connect(function(err) {
        if (err) setTimeout(db_connect, 1000);
    });

    db.on('error', function (err) {
        console.error(err);
        if(err.code === 'PROTOCOL_CONNECTION_LOST') {
            setTimeout(db_connect, 1000);
          } else {
            throw err;
        }
    });
}
db_connect();

// Export JSON Parser and db
module.exports = {
    json_validate: function (str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    },
    db:db
};
