#!/usr/bin/ruby

ENV['GEM_PATH'] = '/home/singpolyma/.gems:/usr/lib/ruby/gems/1.8'

require 'rubygems'
require  'oauth/consumer'
require 'uri'
require 'cgi'

uri = URI::parse('http://www.google.com/m8/feeds/contacts/default/full/batch')

@consumer = OAuth::Consumer.new( 'singpolyma.net', '', {
	:site => "http://#{uri.host}:#{uri.port}",
	:scheme => :header,
	:http_method => :post
})

access_token = OAuth::AccessToken.new(@consumer, ARGV[0], ARGV[1])
r = access_token.post(uri.path, ARGV[2], {'Content-Type' => 'application/atom+xml'})

puts r.body
