#!/bin/bash
. venv/bin/activate
export FLASK_APP="app.py"
export FLASK_DEBUG=1
cd www
flask run

