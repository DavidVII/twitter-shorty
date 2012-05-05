# Twitter Shorty

This is a simple WordPress plugin that allows you to add an unstyled twitter feed to any page on your WordPress site via shortcode. There is a caching system in place to keep you from making to many requests to the twitter API. You can set this manually the timer manually per shortcode or use the default 60 minutes. This plugin is still in development and is not ready for production.

## Installation

Download and install the zipped file using the WordPress dashboard interface. Or do it the l337 way... you should know how to do this by now.

### Usage

Here are some examples:

	[twitter username=DavidVII]

Will generate:

"Follow me on Twitter!" - Links to your twitter page

--

You can customize the message easily with this:

	[twitter username=DavidVII]Custom Follow Message[/twitter]

Generates:
"Custom Follow Message" - Links to your twitter page

--

	[twitter username=DavidVII show_tweets=true]

Will generate the above with 5 of your latest tweets

You can customize how many tweets show up by adding this the [total_tweets] attribute. For example: [twitter username=DavidVII show_tweets=true total_tweets=10]

--

You can also set the timer for the caching system manually by using the [reset_timer] attribute (in minutes).

	[twitter username=DavidVII show_tweets=true reset_timer=10]