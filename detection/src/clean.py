# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="diegocaro"
__date__ ="$Nov 18, 2009 12:10:32 AM$"

import re
import htmlentitydefs


def cleanText(str):
    str = str.decode('utf8', 'ignore')
    str = unescape_html(str)
    str = clean_url(str)
    str = clean_users(str)
    return str

def cleanTextPos(str):
    str = str.decode('utf8', 'ignore')
    str = unescape_html(str)
    str = clean_url(str)
    return str

##
# Removes HTML or XML character references and entities from a text string.
#
# @param text The HTML (or XML) source text.
# @return The plain text, as a Unicode string, if necessary.

def unescape_html(text):
    def fixup(m):
        text = m.group(0)
        if text[:2] == "&#":
            # character reference
            try:
                if text[:3] == "&#x":
                    return unichr(int(text[3:-1], 16))
                else:
                    return unichr(int(text[2:-1]))
            except ValueError:
                pass
        else:
            # named entity
            try:
                text = unichr(htmlentitydefs.name2codepoint[text[1:-1]])
            except KeyError:
                pass
        return text # leave as is
    return re.sub("&#?\w+;", fixup, text)

def clean_url(text):
    return re.sub("http:\/\/[\w.\/]+", "", text)

def clean_users(text):
    return re.sub("@\w+", "", text)
