#!/bin/bash

while true; do
    echo "Starting..."
    date

    cat keywords | while read line; do
      echo $line
      ./searchtwitter.py -l es $line
    done

    echo "Sleeping..."
    sleep 1h

done
