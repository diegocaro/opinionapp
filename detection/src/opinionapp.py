#!/usr/bin/env python
# coding: utf-8

# Run this command before load opinionapp.py
# analyze _freeling -f /usr/share/FreeLing/config/es.cfg


__author__="diegocaro"
__date__ ="$Nov 3, 2009 10:50:08 PM$"

# Imports
import sys
import getopt
import csv

from nltk import NaiveBayesClassifier
from svm import *

#Local Imports
import load
import clean
import tokens
import features
import vector_model
import freeling

#network imports
import select
import socket


# Global Variables


#################################################

# Functions :)



# Main function
def main():
    global subTerms
    global polTerms
    global subjectSVMmodel
    global polarityBayesModel
    global showDetails
    
    subjectFilename = "../corpus/subject-tf-pos-words.csv"
    polarityFilename = "../corpus/polarity-tf-uni-bigrams.csv"

    filekeywords = ""
    keywords = []

    filestopwords = ""
    stopwords = []
    
    showDetails = False

    telnetServer = False
    telnetPort = 50000
    
    noPrompt = False

    opts, args = getopt.getopt(sys.argv[1:], "k:s:dtp:n")
    for o, a in opts:
        if o == "-k":
            filekeywords = a
        elif o == "-s":
            filestopwords = a
        elif o == "-d":
            showDetails = True
        elif o == "-t":
            telnetServer = True
        elif o == "-p":
            telnetPort = int(a)
        elif o == "-n":
            noPrompt = True

    ##
    # Reading keywords and stopwords file
    ##
    keywords = load.readStopwords(filekeywords)
    keywords = [clean.cleanText(w.lower()) for w in keywords]
    #print keywords

    stopwords = load.readStopwords(filestopwords)  
    stopwords.extend(keywords)


    ##
    # Setting server
    ##
    if telnetServer:
        host = ''
        port = telnetPort
        backlog = 5
        size = 1024
        server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        server.bind((host,port))
        server.listen(backlog)
    
        if noPrompt:
            input = [server]
        else:
            input = [sys.stdin, server] #this doesn't work when we load opinionapp.py in background

    print "Loading system... please wait until we work!"
    ##
    # Recovering CORPUS
    ##

    subTerms,sublabels,subvalues,subpresenceindocs,subsumcounters = load.readCSV(subjectFilename)
    
    polTerms,pollabels,polvalues,polpresenceindocs,polsumcounters = load.readCSV(polarityFilename)
        
    #load.writeCSV("subject-pos-words.csv", terms, sumcounters, labels, values)
    
    subjectLabel = sublabels
    subjectTrain = subvalues 

    polarityLabel = pollabels
    polarityTrain = polvalues
        
    print "Corpus loaded."

    if showDetails:
        nsamples = len(subvalues)   
        print "%d samples loaded for subjectivity." % (nsamples)

        nterms = len(subTerms)
        print "%d terms loaded subjectivity." % (nterms)

        nsamples = len(pollabels)   
        print "%d samples loaded for polarity." % (nsamples)

        nterms = len(polTerms)
        print "%d terms loaded for polarity." % (nterms)




    #TF FOR SUBJECTIVITY SVM GAUSSIANO POS+WORDS
    #TF FOR POLARITY BAYES  UNI+BIGRAMS

    subjectSVMparam = svm_parameter(svm_type = C_SVC, C=10, kernel_type = RBF)
    subjectSVMproblem = svm_problem(subjectLabel, subjectTrain)
    subjectSVMmodel = svm_model(subjectSVMproblem,subjectSVMparam)

#    subjectPrediction = subjectSVMmodel.predict(subjectTrain[0])
#    print subjectPrediction
    

    dictrain = [dict(zip(polTerms,freq)) for freq in polarityTrain]          
    train = [[dd, label] for dd,label in zip(dictrain, polarityLabel)]
    polarityBayesModel = NaiveBayesClassifier.train(train)
    
#    polarityPrediction = polarityBayesModel.classify(dictrain[0])     
#    print polarityPrediction
    
    
#        if doc['label'] in ['neg', 'not']:
#            l = -1
#        else:
#            l = +1


    
    print "Classifier trained."
        
#    classifier = train.train(typeclassificator, vectormodel);
#    print "Classifier trained."
#    classifier.show_most_informative_features()
    
    if telnetServer: 
        if noPrompt:
            print "Insert a tweet by telnet running 'telnet localhost %s'" % (telnetPort)        
        else:
            print "Insert a tweet here or by telnet (run 'telnet localhost %s'): " % (telnetPort)
    else:
        print "Insert a tweet: "
    
    running = 1
    while running:        
       if telnetServer: 
            inputready,outputready,exceptready = select.select(input,[],[])

            for s in inputready:

                if s == server:
                    # handle the server socket
                    client, address = server.accept()
                    input.append(client)

                elif s == sys.stdin:
                    # handle standard input

                    data = raw_input()

                    if data == ":QUIT":
                        running = 0
                    else:        
                        resp = detection(data)
                        print resp

                else:
                    # handle all other sockets
                    data = s.recv(size)
                    print data
                    if data == ":QUIT\r\n":
                        s.close()
                        input.remove(s)                    
                    elif data:
                        #s.send(data)
                        resp = detection(data)
                        print resp
                        s.send(resp)
                    else:
                        s.close()
                        input.remove(s)
            
       else:
            data = raw_input()
            if data == "QUIT":
                running = 0
            else:        
                resp = detection(data)
                print resp
    
    if telnetServer: 
        server.close()      
    print "Exit succesful."
         
#detection subsystem  
def detection(tweet):
    resp = None

    subj = dict()
    polr = dict()
   
    polr['raw_text'] = tweet
    subj['raw_text'] = tweet
    
    
    subj['postext'] = clean.cleanTextPos(tweet)
    subj['text'] = clean.cleanText(tweet)

    polr['text'] = clean.cleanText(tweet)       
    

    # Terms as PosTags in Subjectivity
    subj['posterms'] = freeling.getPOS(subj['postext'])
    subj['terms'] = tokens.getTokens(subj['text']) # may be tokens.getTokens(text, stopwords)
            
    # Terms as word Tokens in polarity
    polr['terms'] = tokens.getTokens(polr['text']) # may be tokens.getTokens(text, stopwords)
    
   
    ## SELECT TERMS... BY DEFAULT WORDS!
    ###### completar words o postags
    
    subj['features'] = features.getFeatures(subj['posterms'] + subj['terms'], 'unigrams')
    polr['features'] = features.getFeatures(polr['terms'], 'uni+bigrams')
    
    
#        print subj['features']
#        print polr['features']
    
    subj['vectormodel'] = vector_model.getModel(subTerms, subj['features'], 'tf')
    
    polr['vectormodel'] = vector_model.getModel(polTerms, polr['features'], 'tf')
    polr['dictvectormodel'] = dict( zip(polTerms, polr['vectormodel']) )
    
#        print subj['vectormodel']
#        print polr['vectormodel']
    
    subjectPrediction = subjectSVMmodel.predict(subj['vectormodel'])
    
    if showDetails:
        print "subjectPrediction: ", subjectPrediction
    
    if subjectPrediction == 1:
        polarityPrediction = polarityBayesModel.classify(polr['dictvectormodel']) 
        
        if polarityPrediction == 1:
            resp = "pos"
        else:
            resp = "neg"
        
        if showDetails:
            print "polarityPrediction: ", polarityPrediction
    else:
        resp = "not"
    
    return resp
    

# Help
def print_usage():
    return None

if __name__ == "__main__":
    main()
    
