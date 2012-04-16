#! /usr/bin/python

# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="diegocaro"
__date__ ="$Dec 1, 2009 3:53:06 PM$"

from nltk.tokenize import *
from nltk.probability import FreqDist
import re

def getTokens(text, stopwords=None):
    tok = RegexpTokenizer('[#]?[\w]+', False, False, re.UNICODE | re.MULTILINE | re.DOTALL | re.IGNORECASE )

    tokens = tok.tokenize(text)

    
    words = [w.lower() for w in tokens]
        
    if stopwords is not None:
        words = [w for w in words if w not in stopwords]
        
    return words


#def filterTokens(tokens, N=None):
#    all_terms = FreqDist(tokens)
#    if N is not None:
#        all_terms = all_terms.keys()[:N]
#    return all_terms
    

def filterTokens(tokens, typefeatures=None):
    all_terms = FreqDist(tokens)

    
    if typefeatures == 'unigrams':
        minimal = 2
    elif typefeatures == 'bigrams':    
        minimal = 2
    else:
        minimal = 1
   
    other = FreqDist()
    for freq,term in zip(all_terms.values(),all_terms.keys()):
        if freq >= minimal:
            other.inc(term, freq)
        else:
            break

    return other
