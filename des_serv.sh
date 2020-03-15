#!/bin/bash

systemctl stop apache2
systemctl stop mysql
/opt/lampp/lampp start
