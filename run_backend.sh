#!/bin/bash

PATH_ACTUAL=`pwd`

PATH_LOGS="$PATH_ACTUAL/logs"
PATH_DETECTION="$PATH_ACTUAL/detection/src"
PATH_CAKEPHP="$PATH_ACTUAL/webapp/cake"
PATH_SEARCH="$PATH_ACTUAL/search"
CAKEPHP_APP="opinionApp"

cd $PATH_DETECTION
source ../setpythonpath.sh

echo "Killing old instances"
kill -9 `cat $PATH_LOGS/analyze.pid`
kill -9 `cat $PATH_LOGS/opinionapp.pid`
killall -9 analyzer_server

echo "Removing freeling data"
rm ../tmp/_freeling*

if [ "$1" = "-s" ]
then
    echo "Backend has been stopped."
	exit
fi

# Ready!

echo "Running freeling"
analyze ../tmp/_freeling -f /usr/share/FreeLing/config/es.cfg &
echo $! > $PATH_LOGS/analyze.pid

echo "Running opinionapp.py system"
./opinionapp.py -t -n &
echo $! > $PATH_LOGS/opinionapp.pid

echo "Wait a bit while the system gets up"
sleep 20
cd $PATH_ACTUAL
while true; do
    echo "Starting..."
    date

    cat $PATH_SEARCH/keywords | while read line; do
      echo $line
      $PATH_SEARCH/searchtwitter.py -l es $line
    done

    echo "Converting data from api to mysql data"
    $PATH_CAKEPHP/console/cake -app app searchdata2twitt
    
    echo "Running detection of subjectivity and polarity"
    $PATH_CAKEPHP/console/cake -app app detection

    echo "Sleeping..."
    sleep 1h

done

