
# Run this command before load opinionapp.py
# analyze _freeling -f /usr/share/FreeLing/config/es.cfg
#

from subprocess import *
import re
import string

def change_user(match):
    return string.capitalize(match.group(0)[1:])


def getPOSold(entrada):
    mycmd = 'analyze'
    configfile = '/usr/share/FreeLing/config/es.cfg'
    bufsize = 1

    p = Popen([mycmd, '-f', configfile], stdin=PIPE, stdout=PIPE, shell=False)
    
    postags = []

    hashtags = re.findall(r"(#\w+)", entrada, re.M)
    [postags.append("HASHTAG") for h in hashtags]

    users = re.findall(r"(@\w+)", entrada, re.M)
    [postags.append("USER") for h in users]

#    rt = re.findall(r"(RT:)", entrada, re.M)
#    [postags.append("RT") for h in rt]

    #searching hashtags and users
    user_pattern = re.compile(r"@\w+")
    entrada = user_pattern.sub( change_user, entrada )

    entrada = string.replace(entrada, "#", "")
    entrada = string.replace(entrada, "RT:", "")
    
#    print entrada.encode("utf-8", 'ignore')
    results = p.communicate(entrada.encode("utf-8", 'ignore'))[0]

    for result in results.split("\n"):
        reslist = re.findall(r".*?\s+.*?\s+([A-Za-z0-9]{1,2}).*?$",result, re.U)
        postags.extend(reslist)
    
    return postags


def getPOS(entrada):
    fwrite = open('../tmp/_freeling.in', 'w')
    fread = open('../tmp/_freeling.out', 'r')

    postags = []

    hashtags = re.findall(r"(#\w+)", entrada, re.M)
    [postags.append("HASHTAG") for h in hashtags]

    users = re.findall(r"(@\w+)", entrada, re.M)
    [postags.append("USER") for h in users]

#    rt = re.findall(r"(RT:)", entrada, re.M)
#    [postags.append("RT") for h in rt]

    #searching hashtags and users
    user_pattern = re.compile(r"@\w+")
    entrada = user_pattern.sub( change_user, entrada )

    entrada = string.replace(entrada, "#", "")
    entrada = string.replace(entrada, "RT:", "")
    
#    print entrada.encode("utf-8", 'ignore')
#    results = p.communicate(entrada.encode("utf-8", 'ignore'))[0]
    fwrite.write( entrada.encode("utf-8", 'ignore') )
    fwrite.close()
    results = fread.read()

    for result in results.split("\n"):
        reslist = re.findall(r".*?\s+.*?\s+([A-Za-z0-9]{1,2}).*?$",result, re.U)
        postags.extend(reslist)
    
    return postags

if __name__ == "__main__":
    while True:
        tweet = raw_input()
        print getPOS(tweet)


#    print entrada.encode("utf-8", 'ignore')
#    p.stdin.write(entrada.encode("utf-8", 'ignore'))
#    p.stdin.write("\n")

#    while True:
#        result = p.stdout.readline()
#        if (len(result)>1):
#            print result
#            reslist = re.findall(r".*?\s+.*?\s+([A-Za-z0-9]{1,2}).*?$",result, re.U)
#            postags.extend(reslist)
#        else:
#            break
