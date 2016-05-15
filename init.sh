#!/bin/bash
# Initialise repository for development or deploy on vps
composer update
ln -s vendor/phoxy/phoxy/ phoxy
touch secret.yaml

echo "Do not forget fill secret.yaml"
