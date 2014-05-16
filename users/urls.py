from django.conf.urls import patterns, include, url

urlpatterns = patterns('users.views',
    url(r'^$', 'user_login'),
    url(r'^login$', 'user_login'),
    url(r'^logout$', 'user_logout'),
)