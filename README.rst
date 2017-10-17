Kalories app
==============

Welcome to the Kalories app

This document contains information on how to start using the Kalories app.

Starting containers
----------------------------

To start using this docker configuration you must have installed docker-compose 2 and docker ~1.10
Move in `docker/` :

.. code-block:: console

    $ docker-compose up -d

Docker Composer will create database container, NGINX server and php-fpm service.

Browsing the Kalories Application
-----------------------------

Congratulations! You're now ready to use Kalories.

remember to create tables using queries written in `config/schema.sql` and change your `/etc/hosts` (or C:\System32\drivers\hosts)
to comunicate with application
Then, browse to http://motork.local/

Getting started with Kalories
--------------------------

This distribution is meant to be the starting point for your Silex applications.

A great way to start learning Silex is via the `Documentation`_, which will
take you through all the features of Silex.
