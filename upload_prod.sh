#/bin/bash
rsync -e 'ssh -p 2010' -v -r --delete _build/ coworking-metz@santa-maria.stephane-klein.info:coworking.a-metz.info/www/
