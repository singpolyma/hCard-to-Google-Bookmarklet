#!/usr/bin/ruby

ENV['GEM_PATH'] = '/home/singpolyma/.gems:/usr/lib/ruby/gems/1.8'

require 'rubygems'
require  'oauth/consumer'
require 'uri'
require 'cgi'

uri = URI::parse('http://www.google.com/m8/feeds/contacts/default/full')

@consumer = OAuth::Consumer.new( 'singpolyma.net', '', {
	:site => "http://#{uri.host}:#{uri.port}",
	:scheme => :query_string,
	:http_method => :get
})

access_token = OAuth::AccessToken.new(@consumer, ARGV[0], ARGV[1])
r = access_token.get(uri.path + '?alt=json&max-results=9999')

if r.code != '200'
	warn r.body
end

puts r.body
