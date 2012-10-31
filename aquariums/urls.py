from django.conf.urls import patterns, include, url

urlpatterns = patterns('aquariums.views',
    url(r'^$', 'aquariums'),
    url(r'^(?P<aquarium_id>\d+)/$', 'aquarium_details'),
)

