#!/bin/bash
# Initialise repository for development or deploy on vps
composer update
ln -s vendor/phoxy/phoxy/ phoxy
ln -s ../vendor/phoxy/snippets ejs/snippets
ln -s ../../networks networks
touch secret.yaml
mkdir networks

echo "Do not forget fill secret.yaml and"
echo CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
