# encoding: utf-8
require 'rubygems'
require 'bundler'

Bundler.require

class App < Sinatra::Base
  configure do
    # Sinatra configuration and settings
    register Sinatra::ConfigFile
    config_file 'config/settings.yml'

    # Session support
    enable :sessions

    use OmniAuth::Builder do
      settings = App.settings
      provider :twitter, settings.consumer_key, settings.consumer_secret
    end
  end

  # Helpers
  helpers do
    def current_user
      # Enforce user is logged in, and we have real data
      !session[:uid].nil? && !session[:nickname].nil?
    end
  end

  # Enforce Auth
  before do
    pass if request.path_info =~ /^\/auth\// || request.path_info == '/' || request.path_info == '/logout'
    redirect to '/auth/twitter' unless current_user
  end

  get '/' do
    # Meta
    @title        = 'Beispanama — Comparte el fervor por tu equipo favorito del béisbol nacional en Twitter'
    @description  = 'Beispanama: Comparte el fervor por tu equipo favorito del béisbol nacional en Twitter'
    @keywords     = 'beisbol-panama,baseball-panama,beis-panama,twitter-panama,deportes-panama'

    erb :home
  end

  get '/logout' do
    session.clear
    redirect to '/'
  end

  get '/process' do
    # User information
    @usr = session

    # Flags
    @flags = {
      'bocas'        => 'Bocas Del Toro',
      'cocle'        => 'Coclé',
      'colon'        => 'Colón',
      'chiriqui'     => 'Chiriquí',
      'occidente'    => 'Chiriquí Occidente',
      'darien'       => 'Darién',
      'herrera'      => 'Herrera',
      'los_santos'   => 'Los Santos',
      'metro'        => 'Panamá Metro',
      'oeste'        => 'Panamá Oeste',
      'veraguas'     => 'Veraguas'
    }

    # Meta
    @title        = 'Beispanama — Cambia tu avatar'
    @description  = 'Cambia tu avatar de Twitter, usando la bandera de tu equipo favorito del béisbol nacional de Panamá'
    @keywords     = 'beisbol-panama,baseball-panama,beis-panama,twitter-panama,deportes-panama'

    erb :process
  end

  post '/process' do
    # Configure REST Twitter Client
    client = Twitter::REST::Client.new do |config|
      config.consumer_key        = settings.consumer_key
      config.consumer_secret     = settings.consumer_secret
      config.access_token        = session[:token]
      config.access_token_secret = session[:secret]
    end

    # Prepare avatar
    image = MiniMagick::Image.open(session[:image])
    result = image.composite(MiniMagick::Image.open("public/flags/#{params[:equipo]}.png", "png")) do |c|
      c.gravity 'southwest'
    end
    avatar_path = "avatars/#{session[:nickname]}.png"
    result.write avatar_path

    # Update avatar on Twitter
    client.update_profile_image(File.open(avatar_path))

    # Send message
    if params[:send_tweet] && params[:send_tweet] === 'yes'
      client.update 'Yo apoyo a mi equipo favorito de beis en Panamá usando http://www.beispanama.com por @pixmat. #BeisPanama'
    end

    # Follow pixmat :)
    if params[:follow] && params[:follow] === 'yes'
      client.follow 'pixmat'
    end

    #
    @title        = 'Beispanama — Avatar cambiado!'
    @description  = ''
    @keywords     = ''

    erb :done
  end

  get '/auth/twitter/callback' do
    session[:uid]       = env['omniauth.auth']['uid']
    session[:token]     = env['omniauth.auth']['credentials']['token']
    session[:secret]    = env['omniauth.auth']['credentials']['secret']
    session[:nickname]  = env['omniauth.auth']['info']['nickname']
    session[:name]      = env['omniauth.auth']['info']['name']
    session[:image]     = env['omniauth.auth']['info']['image'].gsub! 'normal', 'bigger'

    redirect to '/process'
  end

  get '/auth/failure' do
    erb :error
  end
end