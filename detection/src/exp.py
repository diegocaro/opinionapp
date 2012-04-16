import load
import testing

import sys
import getopt

import nltk

from nltk.metrics import ConfusionMatrix
from svm import *
 
classifier = 'svm'
Kfold = 5
filename = '../newdata/subject-unigrams-pos.csv'
filename2 = None
kerneltype = 'rbf'


opts, args = getopt.getopt(sys.argv[1:], "d:k:c:t:e:")
for o, a in opts:
    if o == "-d":
        filename = a
    elif o == "-e":
        filename2 = a
    elif o == "-c":
        classifier = a
    elif o == "-k":
        Kfold = a
    elif o == "-t":
        kerneltype = a


terms,labels,values,presenceindocs,sumcounters = load.readCSV(filename)

if filename2 is not None:
    terms2,labels2,values2,presenceindocs2,sumcounters2 = load.readCSV(filename2)
    
    terms.extend(terms2)
    presenceindocs.extend(presenceindocs2)
    sumcounters.extend(sumcounters2)
    
    for i in range(len(values2)):
        values[i].extend(values2[i])
        
    filename += " + %s" % (filename2)


nsamples = len(values)   
print "%d samples loaded." % (nsamples)

nterms = len(terms)
print "%d terms loaded." % (nterms)

setlabel = list(set(labels))
setlabel.sort(reverse=True)

presencevalues = load.convert('presence', values)
tfidfvalues = load.convert('tfidf', values, presenceindocs)
tfvalues = values


coded = {'presence':presencevalues, 'tfidf': tfidfvalues, 'tf': tfvalues}

#print presencevalues[0][:10]
#print tfidfvalues[0][:10]

#samples = presencevalues
for typevectormodel, samples in zip(coded.keys(), coded.values()):
    print "1. Separating samples..."
    trains,tests,labelstrain,labelstest = testing.cross_validation(samples, labels, Kfold)


    print "2. Doing testing..."
    total_correct = 0
    confmatrix = []
    
    doingtest = 0
    
    for train,test,labeltrain,labeltest in zip(trains,tests,labelstrain,labelstest):
        print "Doing test number ", doingtest
        doingtest+=1
    
        predictions = []
        if classifier == 'svm':
            
            
            if kerneltype == 'linear':
                param = svm_parameter(svm_type = C_SVC, kernel_type = LINEAR)
            elif kerneltype == 'polynomial':
                param = svm_parameter(svm_type = C_SVC, C=10, kernel_type = POLY)
            elif kerneltype == 'rbf':
                param = svm_parameter(svm_type = C_SVC, C=10, kernel_type = RBF)
            
            problem = svm_problem(labeltrain, train)
            model = svm_model(problem,param)
        elif classifier == 'naivebayes':          
            dictrain = [dict([(t,f) for t,f in zip(terms,freq)]) for freq in train]          
            train = [[dd, label] for dd,label in zip(dictrain, labeltrain)]
            
            test = [dict([(t,f) for t,f in zip(terms,freq)]) for freq in test]          
            model = nltk.NaiveBayesClassifier.train(train)
      
        

        for i in range(len(test)):
            
            if classifier == 'svm':
                prediction = model.predict(test[i])
            elif classifier == 'naivebayes':          
                prediction = model.classify(test[i])        
            

            if prediction == labeltest[i]:
                total_correct = total_correct + 1 

            predictions.append(prediction)
        
        _cm = ConfusionMatrix(labeltest,predictions)
        confmatrix.append(_cm)
    #svmwrapper.do_cross_validation(samples, labels, param, Kfold)
    
    
    (pre, rec, fmea) = testing.metrics(confmatrix, setlabel, 1)
    acc = 100.0 * total_correct / nsamples
    """   
    print "Labels: ", labels
    print "_Labels: ", _labels
    print "TypeTerms: ", typeterms
    print "TypeFeatures: ", typefeatures
    print "TypeModel: ", typemodel

    """
    print "##########################################"
    print "Filename: ", filename
    print "VectorModel: %s" % typevectormodel
    print "Classifier: ", classifier
    print "Presicion: ", pre
    print "Recall: ", rec
    print "F-measure(1): ", fmea
    print "Accuracy = %g%%" % (acc)
    print "##########################################"
    
