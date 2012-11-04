from django.conf.urls import patterns, include, url

urlpatterns = patterns('waterprofiles.views',
    url(r'^$', 'waterprofiles'),
#    url(r'^(?P<aquarium_id>\d+)/$', 'aquarium_details'),
)

