#!/usr/bin/ruby

ENV['GEM_PATH'] = '/home/singpolyma/.gems:/usr/lib/ruby/gems/1.8'

require 'rubygems'
require  'oauth/consumer'
require 'uri'

request_token_uri = URI::parse('https://www.google.com/accounts/OAuthGetRequestToken')
access_token_uri = URI::parse('https://www.google.com/accounts/OAuthGetAccessToken')
authorize_uri = URI::parse('https://www.google.com/accounts/OAuthAuthorizeToken')

@consumer = OAuth::Consumer.new( 'singpolyma.net', '', {
	:site => "https://#{request_token_uri.host}",
	:scheme => :query_string,
	:http_method => :get,
	:request_token_path => "#{request_token_uri.path}?scope=http://www.google.com/m8/feeds/",
	:access_token_path => "#{access_token_uri.path}",
	:authorize_url => authorize_uri.to_s
})

if ARGV[1]

	request_token = OAuth::RequestToken.new(@consumer, ARGV[0], ARGV[1])
	access_token = request_token.get_access_token
	
	puts access_token.token
	puts access_token.secret

else

	request_token = @consumer.get_request_token

	puts request_token.token
	puts request_token.secret
	puts authorize_uri.to_s + "?oauth_token=#{request_token.token}&oauth_callback=http://singpolyma.net/contacts2google/auth_finish.php"

end
