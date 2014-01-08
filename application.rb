# encoding: utf-8

class App < Sinatra::Base
  # Session support
  enable :sessions

  # Helpers
  helpers do
    def current_user
      !session[:uid].nil?
    end
  end

  # Enforce Auth
  before do
    pass if request.path_info =~ /^\/auth\//

    redirect to '/auth/twitter' unless current_user
  end

  get '/' do
    # Meta
    @title        = 'Beispanama — Comparte el fervor por tu equipo favorito del béisbol nacional en Twitter'
    @description  = 'Beispanama: Comparte el fervor por tu equipo favorito del béisbol nacional en Twitter'
    @keywords     = 'beisbol-panama,baseball-panama,beis-panama,twitter-panama,deportes-panama'

    erb :home
  end

  get '/process' do
    # User information
    @usr = session

    # Flags
    @flags = {
      "bocas"        => "Bocas Del Toro",
      "cocle"        => "Coclé",
      "colon"        => "Colón",
      "chiriqui"     => "Chiriquí",
      "occidente"    => "Chiriquí Occidente",
      "darien"       => "Darién",
      "herrera"      => "Herrera",
      "los_santos"   => "Los Santos",
      "metro"        => "Panamá Metro",
      "oeste"        => "Panamá Oeste",
      "veraguas"     => "Veraguas"
    }

    # Meta
    @title        = "Beispanama — Cambia tu avatar"
    @description  = "Cambia tu avatar de Twitter, usando la bandera de tu equipo favorito del béisbol nacional de Panamá"
    @keywords     = "beisbol-panama,baseball-panama,beis-panama,twitter-panama,deportes-panama"

    erb :process
  end

  post '/process' do
    # Configure REST Twitter Client
    # client = Twitter::REST::Client.new do |config|
    #   config.consumer_key        = settings.consumer_key
    #   config.consumer_secret     = settings.consumer_secret
    #   config.access_token        = session[:token]
    #   config.access_token_secret = session[:secret]
    # end

    # Prepare avatar
    image = MiniMagick::Image.open(session[:image])
    result = image.composite(MiniMagick::Image.open("public/flags/#{params[:equipo]}.png", "png")) do |c|
      c.gravity "southwest"
    end
    avatar_path = "avatars/#{session[:nickname]}.png"
    result.write avatar_path

    # Update avatar on Twitter
    # client.update_profile_image(avatar_path)
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
  end

  use OmniAuth::Builder do
    provider :twitter, settings.consumer_key, settings.consumer_secret
  end
end