Kalories app
==============

Welcome to the Kalories app

This document contains information on how to start using the Kalories app.

Starting containers
----------------------------

To start using this docker configuration you must have installed docker-compose 2 and docker ~1.10:
.. code-block:: console
    $ cd docker/
    $ docker-compose up -d

Docker Composer will create database container, NGINX server and php-fpm service.

Install dependencies
-----------------------------
Remember to install project dependecy using composer
.. code-block:: console
    $ composer install

Browsing the Kalories Application
-----------------------------

Congratulations! You're now ready to use Kalories.

remember to create tables using queries written in `config/schema.sql` and change your `/etc/hosts` (or C:\System32\drivers\hosts)
to comunicate with application
Then, browse to http://motork.local/
