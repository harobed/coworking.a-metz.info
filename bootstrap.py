#!/usr/bin/env python
## -*- coding: utf-8 -*-
"""Create a custom "virtual" Python installation
"""

import virtualenv

from os.path import join as path_join
from tempfile import mkdtemp
from subprocess import call as subprocess_call
from shutil import rmtree

try:
    from urllib.request import urlopen
except ImportError:
    from urllib2 import urlopen  # NOQA

tmp_dir = None


def local_adjust_options(options, args):
    global tmp_dir

    tmp_dir = mkdtemp()
    print('download setuptools...')
    f = open(path_join(tmp_dir, 'setuptools-latest.tar.gz'), 'w')
    f.write(urlopen('https://pypi.python.org/packages/source/s/setuptools/setuptools-1.1.1.tar.gz#md5=c8d19510c03b0e2e01880c0d8f080083').read())  # NOQA
    f.close()
    print('setuptools downloaded')

    print('download pip...')
    f = open(path_join(tmp_dir, 'pip-latest.tar.gz'), 'w')
    f.write(urlopen('https://pypi.python.org/packages/source/p/pip/pip-1.4.1.tar.gz#md5=6afbb46aeb48abac658d4df742bff714').read())  # NOQA
    f.close()
    print('pip downloaded')

    if len(args) == 0:
        options.search_dirs = [tmp_dir]
        args.append('.')


def local_after_install(options, home_dir):
    global tmp_dir

    subprocess_call([
        path_join('bin', 'pip'),
        'install', '-r', 'requirements.txt'
    ])
    subprocess_call([path_join('npm'), 'install'])

    rmtree(tmp_dir)

# Monkey patch
virtualenv.adjust_options = local_adjust_options
virtualenv.after_install = local_after_install


virtualenv.main()
