#!/usr/bin/env python
# -*- coding: utf-8 -*-
#
#  searchtwitter.py
#  
#
#  Created by Diego Caro on 22-08-09.
#  Copyright (c) 2009 __MyCompanyName__. All rights reserved.
#
import getopt
import simplejson
import urllib2
import sys
import MySQLdb #you must install python-mysqldb package (in ubuntu)
from urllib import urlencode

mysqlhost = "localhost"
mysqluser = "opinionapp"
mysqlpass = "opinionpass"
mysqldb = "opinionapp"

def search(query, resultnum=None, lang=None, since_id=None):
    output = []
    
    q = []
    rpp = 100
    q.append(urlencode({'q': query}))
    if not since_id is None:
        q.append(urlencode({'since_id': since_id}))
    if not lang is None:
        q.append(urlencode({'lang': lang}))
    if not resultnum is None:
        rpp = resultnum
    q.append(urlencode({'rpp': rpp}))
    baseurl = 'http://search.twitter.com/search.json'
    
    
    url = baseurl + '?' + '&'.join(q)
    print url
       
    response = urllib2.urlopen(url)
    data = simplejson.load(response)
    output.append(data)
    
    while 'next_page' in data:
        url = baseurl + data['next_page']
        print url
        response = urllib2.urlopen(url)
        data = simplejson.load(response)
        output.append(data)
    
    return output

def save_data(tag, query, last_id, last_date, results):
    conn = MySQLdb.connect (host = mysqlhost,
                             user = mysqluser,
                             passwd = mysqlpass,
                             db = mysqldb)

    

    c = conn.cursor()
    
    for result in results:
        # Sql statement
        t = [tag, query, last_id, last_date, simplejson.dumps(result)]
        # Insert a row of data
        c.execute('INSERT INTO searchdata (tag, query, last_id, last_date, data) VALUES (%s,%s,%s,%s,%s)', t)

    # Save (commit) the changes
    conn.commit()

    # We can also close the cursor if we are done with it
    c.close()


def load_data():
    return None
    
def get_lastid(query):
    conn = MySQLdb.connect (host = mysqlhost,
                             user = mysqluser,
                             passwd = mysqlpass,
                             db = mysqldb)
    
    c = conn.cursor()
    c.execute("SELECT MAX(last_id) FROM searchdata WHERE query = %s", (query))

    res = c.fetchone()
    if len(res) > 0:
        return res[0]
    else:
        return None

def print_usage():
    sys.stderr.write("""
    Usage: %s [-r search_result_number] [-l language] [-t tag]
        terms ...

    -l language = Filter search by language.
    -r search_result_number = Number of results to pull from twitter searches.
    -t tag = tag when store data
""" % sys.argv[0])

if __name__ == '__main__':
    optlist, args = getopt.getopt(sys.argv[1:], 'l:r:s:')
    if not args:
        sys.stderr.write("You must specify search terms\n")
        print_usage()
        raise SystemExit, 1
    query = " ".join(args)
	
    optd = dict(optlist)

    rpp = None
    if '-r' in optd:
        rpp = optd['-r']

    lang = None
    if '-l' in optd:
        lang = optd['-l']
        
    #since_id = None
    since_id = get_lastid(query)
#    if '-s' in optd:
#        since_id = optd['-s']
    
    if '-t' in optd:
        tag = optd['-t']
    else:
        tag = ''
        
    try:
        response = search(query, rpp, lang, since_id )
        #print response
        if len(response[0]['results']) > 0:
            save_data(tag, query, response[0]['results'][0]['id'], response[0]['results'][0]['created_at'], response)
        
    except urllib2.HTTPError, e:
        sys.stderr.write("Cannot connect to Twitter\n")
        sys.stderr.write(str(e))
        sys.stderr.write("\n")
