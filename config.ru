require 'rubygems'
require 'bundler'

Bundler.require

# Sinatra configuration and settings
register Sinatra::ConfigFile
config_file 'config/settings.yml'

# Start the magic
require './application'
run App