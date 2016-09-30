import os
import ConfigParser
from flask import Flask
from flask import request, render_template, redirect, url_for
from sqlalchemy import create_engine
from sqlalchemy import Table, Column, Integer, String, Date, Enum, MetaData, ForeignKey
from sqlalchemy.dialects.mysql import INTEGER, SMALLINT
from sqlalchemy.sql import select, text, func
from datetime import date

app = Flask(__name__)

config = ConfigParser.ConfigParser()
config.read(os.path.join(os.path.abspath(os.path.dirname(__file__)), 'config.ini'))
app.config['SQLALCHEMY_DATABASE_URI'] = config.get('database', 'url')
engine = create_engine(config.get('database', 'url'))

@app.route('/')
def index():
    qry = text("""SELECT aquariumID, name FROM Aquariums WHERE userID=1""")
    aquariums = engine.execute(qry).fetchall()
    return render_template('index.html', aquariums=aquariums)

@app.route('/aquarium/<int:aquariumID>')
def aquarium(aquariumID):
    qry = text("""SELECT aquariumID, name, createdAt, measurementUnits,
        capacity, length, width, height, location
        FROM Aquariums
        WHERE aquariumID = :aquariumID""")
    aquarium = engine.execute(qry, aquariumID=aquariumID).fetchone()

    qry = text("""SELECT equipmentID, name, maintInterval FROM Equipment
        WHERE aquariumID = :aquariumID
        AND deletedAt IS NULL""")
    equipment = engine.execute(qry, aquariumID=aquariumID).fetchall()

    qry = text("""SELECT aquariumLogID, logDate, summary
        FROM AquariumLogs
        WHERE aquariumID = :aquariumID
        ORDER BY aquariumLogID DESC LIMIT 10""")
    logs = engine.execute(qry, aquariumID=aquariumID).fetchall()

    qry = text("""SELECT name FROM AquariumLogFavorites
        WHERE aquariumID = :aquariumID""")
    actions = engine.execute(qry, aquariumID=aquariumID).fetchall()

    return render_template('aquarium.html', aquarium=aquarium,
                            equipment=equipment,
                            logs = logs,
                            actions = actions)
