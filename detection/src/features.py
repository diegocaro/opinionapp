#! /usr/bin/python

# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="diegocaro"
__date__ ="$Dec 1, 2009 3:53:06 PM$"

from nltk.probability import FreqDist
from nltk.util import bigrams
from nltk.util import trigrams

def getFeatures(tokens, typefeat='unigrams'):

    if typefeat == 'unigrams':
        _features = FreqDist(tokens)

    elif typefeat == 'bigrams':
        _bigrams = bigrams(tokens)
        _features = FreqDist(_bigrams)

    elif typefeat == 'uni+bigrams':
        _bigrams = bigrams(tokens)
        _features = FreqDist(_bigrams + tokens)

    return _features
