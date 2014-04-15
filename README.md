::

    $ python bootstrap.py
    $ export LESS_BIN=`pwd`/node_modules/less/bin/lessc
    $ bower install
    $ ./assets_postinstall.sh
    $ bin/tacot -v www


In another terminal, start :

::

    $ bin/static localhost 8081 _build/

Browse to http://localhost:8081/
