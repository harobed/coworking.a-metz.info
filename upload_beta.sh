#/bin/bash
rsync -e 'ssh -p 2010' -v -r --delete _build/ coworking-metz@santa-maria.stephane-klein.info:beta.coworking.a-metz.info/
