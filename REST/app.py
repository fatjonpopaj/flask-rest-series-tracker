from flask import Flask, jsonify, request
import mysql.connector

app = Flask(__name__)

def getConnection():
    connection = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="webtechdbgruppe20"
    )
    return connection

@app.route('/user', methods=['GET'])
def getUser():
    connection = getConnection()
    cursor = connection.cursor()
    cursor.execute("SELECT * FROM user")
    result = cursor.fetchall()
    cursor.close()
    connection.close()
    return jsonify(result), 200

@app.route('/series', methods=['GET'])
def getSeries():
    connection = getConnection()
    cursor = connection.cursor()
    cursor.execute("SELECT * FROM series")
    result = cursor.fetchall()
    cursor.close()
    connection.commit()
    connection.close()
    return jsonify(result), 200

@app.route('/userseries/<userid>/<seriesid>', methods=['GET'])
def getUserseries(userid, seriesid):
    connection = getConnection()
    cursor = connection.cursor()
    sql = "SELECT * FROM userseries WHERE (seriesID=%s) AND (userID=%s)"
    cursor.execute(sql, (seriesid, userid))
    result = cursor.fetchall()
    cursor.close()
    connection.commit()
    connection.close()
    return jsonify(result), 200

@app.route('/userseries/<seriesid>', methods=['GET'])
def getUserseriesWithId(seriesid):
    connection = getConnection()
    cursor = connection.cursor()
    sql = "SELECT * FROM userseries WHERE (seriesID=%s)"
    cursor.execute(sql, (seriesid,))
    result = cursor.fetchall()
    cursor.close()
    connection.commit()
    connection.close()
    return jsonify(result), 200

@app.route('/series/add', methods=['POST'])
def addSeries():
    connection = getConnection()
    cursor = connection.cursor()
    sql = "INSERT INTO series (title, seasons, genre, plattform) VALUES (%s, %s, %s, %s)"
    cursor.execute(sql, (request.form['title'],request.form['seasons'],request.form['genre'],request.form['plattform']))
    cursor.close()
    connection.commit()
    connection.close()
    return "", 200

@app.route('/userseries/add/<seriesid>/<userid>/<rating>', methods=['POST'])
def addSeriesToUser(seriesid, userid, rating):

    connection = getConnection()
    cursor = connection.cursor()

    sql = "SELECT * FROM userSeries WHERE (seriesID=%s) AND (userID=%s)"
    cursor.execute(sql, (seriesid, userid))
    result = cursor.fetchall()

    if (len(result) == 0):
        sql = "INSERT INTO userSeries (userID, seriesID, rating) VALUES (%s, %s, %s)"
        cursor.execute(sql, (userid, seriesid, rating))

    cursor.close()
    connection.commit()
    connection.close()

    return "", 200

@app.route('/series/<userid>', methods=['GET'])
def getSeriesByID(userid):
    connection = getConnection()
    cursor = connection.cursor()
    cursor.execute("SELECT * FROM series s JOIN userseries u ON (s.seriesID = u.seriesID) WHERE u.userID = %s", (userid,))
    result = cursor.fetchall()
    cursor.close()
    connection.commit()
    connection.close()
    return jsonify(result), 200

@app.route('/userseries/delete/<seriesid>/<userid>', methods=['DELETE'])
def deleteUserseries(seriesid, userid):
    connection = getConnection()
    cursor = connection.cursor()
    cursor.execute("DELETE FROM userseries WHERE seriesID = %s AND userID = %s", (seriesid, userid))
    connection.commit()
    cursor.close()
    connection.close()
    return "", 200

@app.route('/series/delete/<seriesid>', methods=['DELETE'])
def deleteSeries(seriesid):
    connection = getConnection()
    cursor = connection.cursor()
    cursor.execute("DELETE FROM series WHERE seriesID = %s", (seriesid,))
    connection.commit()
    cursor.close()
    connection.close()
    return "", 200

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
