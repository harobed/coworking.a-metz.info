==========
My project
==========

Installation
============

Prerequises :

* `Python <http://www.python.org/download/releases/2.7.5/>`_
* Install `node.js package <http://nodejs.org/>`_.
* Install `RubyGems <https://rubygems.org/>`_ already installed on Mac OS X

::

    $ python bootstrap.py
    $ npm install bower
    $ gem install sass compass


Build
=====

::

    $ bower install
    $ bin/tacot src/ -o build/


Other tasks
===========

Use fabric to execute some standard tasks :

::

    $ bin/fab -l
    Available commands:

        build
        clean
        upload_beta
        upload_prod

To build :

::

    $ bin/fab build

To upload to production hosting :

::

    $ bin/fab upload_prod

...
