from nltk.probability import FreqDist

def getModel(all_terms, features, typemodel='presence'):
    model = [] # this was a dict, now is a list
    
    document_words = set(features.keys())

    if (typemodel == 'presence'):
        for word in all_terms:
            #model[word] = (word in document_words)
            model.append( word in document_words )
    
    elif (typemodel == 'tf'):
        for word in all_terms:
            model.append( features[word] )
    
    
    return model
