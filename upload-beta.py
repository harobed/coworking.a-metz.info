#!/usr/bin/python
import os
here = lambda x: os.path.join(os.path.abspath(os.path.dirname(__file__)), x)

os.system("rsync -e 'ssh -p 2225' -v -r --delete %s coworking-metz@harobed-vs1.stephane-klein.info:beta.coworking.a-metz.info/" % here('www/'))
