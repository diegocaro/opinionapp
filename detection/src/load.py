import csv
import math
def readCSV(filename):
    docs = []
    f = open(filename, 'r')

    _docs = csv.reader(f)

    data = []
    for _doc in _docs:
        data.append(_doc)

    f.close()
    
    
    terms = data[0][1:]
    labels = [int(v[0]) for v in data[2:]]
    values = [[int(t) for t in v[1:]] for v in data[2:]]


    sumcounters = [int(t) for t in data[1][1:]]
    presenceindocs = [0]*len(terms)

    for v in values:
        for i in range(len(presenceindocs)):
            if v[i] >= 1:
                presenceindocs[i] = presenceindocs[i]+1
    
#    out = {
#        'terms': terms,
#        'labels': labels,
#        'values': values,
#        'presenceindocs': presenceindocs,
#        'sumcounters': sumcounters }
    
    return terms,labels,values,presenceindocs,sumcounters
    

def convert(tipo, values, presenceindocs=False):
    newvalues = []
    
    if tipo=='presence':
        for value in values:
            _v = []
            for v in value:
                if v > 0:
                    k = 1
                else:
                    k = 0
                _v.append(k)

            newvalues.append(_v)

    elif tipo=='tfidf':
        totaldocs = len(values)
        
    
        for value in values:
            _v = []
            presindoc = 0.0
            for v in value:
                if v > 0:
                    presindoc = presindoc + 1 
            
            if presindoc == 0:
                presindoc = presindoc+1
                
            for v,p in zip(value, presenceindocs):
                #tf = v/presindoc
                tf = v
                idf = math.log1p(totaldocs/(1+p))
                tfidf = tf*idf

#                if v > 0:
#                    print "v", v
#                    print "presindoc", presindoc
#                    print "tf", tf
#
#                    print "idf", idf
#                    print "totaldocs", totaldocs
#                    print "presenceindocs", p
#                    print "tfidf:", tfidf

                _v.append(tfidf)
            newvalues.append(_v)
    
    
    
    
    return newvalues
    
    
    
    
    
    
    
    
    
def readStopwords(filename):
    stopwords = []

    if len(filename) > 0:
        lines = open(filename, 'r').readlines()
        
        for line in lines:
            terms = line.split()
            stopwords.extend(terms)      

    return stopwords
    
    
def writeCSV(filename, terms, sumcounters, labels, values):
    writer = csv.writer(open(filename, "wb"))

    row1 = [term for term in terms]
    row1.insert(0, 'class')
    writer.writerow(row1) 
    
    ## Corpus Freq
    row2 = [0] + sumcounters
    writer.writerow(row2) 
    
    ## For each doc, show the class and frequency
    for values, label in zip(values,labels):
        rowx = [ label ]
        rowx.extend( values )
        
        writer.writerow(rowx) 
